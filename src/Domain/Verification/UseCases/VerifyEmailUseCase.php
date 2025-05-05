<?php

declare(strict_types=1);

namespace Domain\Verification\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Domain\Verification\Notifications\EmailVerifiedNotification;
use Illuminate\Support\Facades\DB;
use Support\Token\Models\Token;
use Support\Token\Repositories\TokenRepositoryInterface;

class VerifyEmailUseCase extends UseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(Token $token): User
    {
        $token->loadMissing('user');

        throw_if(
            condition: !$token->user,
            exception: new HttpException(responseCode: ResponseCodeEnum::TOKEN_INVALID)
        );

        /** @var User $user */
        $user = $token->user;

        return DB::transaction(function () use (
            $token,
            $user,
        ): User {
            $user = $this->userRepository->verifyEmail($user);

            $this->tokenRepository->markUsed($token);

            $user->notify(new EmailVerifiedNotification());

            return $user;
        }, attempts: 5);
    }
}
