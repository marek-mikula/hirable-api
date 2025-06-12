<?php

declare(strict_types=1);

namespace Domain\Password\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Password\Notifications\PasswordResetRequestNotification;
use Domain\User\Repositories\UserRepositoryInterface;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Repositories\Input\TokenStoreInput;
use Support\Token\Repositories\TokenRepositoryInterface;

class RequestPasswordResetUseCase extends UseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return;
        }

        $throttleMinutes = config(sprintf('token.throttle.%s', TokenTypeEnum::RESET_PASSWORD->value));

        if (!empty($throttleMinutes)) {
            $nextPossibleAt = now()->subMinutes((int) $throttleMinutes);

            $existingToken = $this->tokenRepository->findLatestByTypeAndUser(TokenTypeEnum::RESET_PASSWORD, $user);

            // user requested the reset password for this
            // email not even that long ago => deny sending
            // another request
            if ($existingToken && !$existingToken->is_expired && $existingToken->created_at->gt($nextPossibleAt)) {
                throw new HttpException(responseCode: ResponseCodeEnum::RESET_ALREADY_REQUESTED, data: [
                    'retryInMinutes' => ceil($existingToken->created_at->diffInMinutes($nextPossibleAt, absolute: true)),
                ]);
            }
        }

        $token = $this->tokenRepository->store(new TokenStoreInput(
            type: TokenTypeEnum::RESET_PASSWORD,
            user: $user,
        ));

        $user->notify(new PasswordResetRequestNotification(token: $token));
    }
}
