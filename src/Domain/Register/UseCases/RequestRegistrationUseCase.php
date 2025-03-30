<?php

namespace Domain\Register\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Repositories\Token\Input\StoreInput;
use App\Repositories\Token\TokenRepositoryInterface;
use App\UseCases\UseCase;
use Domain\Register\Notifications\RegisterRequestNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;

class RequestRegistrationUseCase extends UseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {}

    public function handle(string $email): void
    {
        $throttleMinutes = config('token.throttle.registration');

        if (! empty($throttleMinutes)) {
            $nextPossibleAt = now()->subMinutes((int) $throttleMinutes);

            $existingToken = $this->tokenRepository->findLatestByTypeAndEmail(TokenTypeEnum::REGISTRATION, $email);

            // user requested the registration for this
            // email not even that long ago => deny sending
            // another request
            if ($existingToken && ! $existingToken->is_expired && $existingToken->created_at->gt($nextPossibleAt)) {
                throw new HttpException(responseCode: ResponseCodeEnum::REGISTRATION_ALREADY_REQUESTED, data: [
                    'retryInMinutes' => ceil($existingToken->created_at->diffInMinutes($nextPossibleAt, absolute: true)),
                ]);
            }
        }

        try {
            // wrap sending email and creating token to
            // transaction, so the token gets deleted,
            // if email sending fails
            DB::transaction(function () use ($email): void {
                $token = $this->tokenRepository->store(StoreInput::from([
                    'type' => TokenTypeEnum::REGISTRATION,
                    'data' => [
                        'email' => $email,
                    ],
                ]));

                Notification::route('mail', $email)->notifyNow(new RegisterRequestNotification(token: $token));
            }, attempts: 5);
        } catch (\Exception) {
            throw new HttpException(responseCode: ResponseCodeEnum::CLIENT_ERROR, message: 'Mail is not reachable.');
        }
    }
}
