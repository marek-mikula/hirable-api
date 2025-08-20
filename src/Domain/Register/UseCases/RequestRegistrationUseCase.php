<?php

declare(strict_types=1);

namespace Domain\Register\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Register\Notifications\RegisterRequestNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Repositories\Input\TokenStoreInput;
use Support\Token\Repositories\TokenRepositoryInterface;
use Support\Token\Services\TokenConfigService;

class RequestRegistrationUseCase extends UseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly TokenConfigService $tokenConfigService,
    ) {
    }

    public function handle(string $email): void
    {
        $throttle = $this->tokenConfigService->getTokenThrottle(TokenTypeEnum::REGISTRATION);

        if (!empty($throttle)) {
            $nextPossibleAt = now()->subMinutes($throttle);

            $existingToken = $this->tokenRepository->findLatestByTypeAndEmail(TokenTypeEnum::REGISTRATION, $email);

            // user requested the registration for this
            // email not even that long ago => deny sending
            // another request
            if ($existingToken && !$existingToken->is_expired && $existingToken->created_at->gt($nextPossibleAt)) {
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
                $token = $this->tokenRepository->store(new TokenStoreInput(
                    type: TokenTypeEnum::REGISTRATION,
                    data: [
                        'email' => $email,
                    ],
                ));

                Notification::route('mail', $email)->notifyNow(new RegisterRequestNotification(token: $token));
            }, attempts: 5);
        } catch (\Exception) {
            throw new HttpException(responseCode: ResponseCodeEnum::CLIENT_ERROR, message: 'Mail is not reachable.');
        }
    }
}
