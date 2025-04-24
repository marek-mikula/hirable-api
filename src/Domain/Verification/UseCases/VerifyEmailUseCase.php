<?php

namespace Domain\Verification\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Models\Token;
use App\Models\User;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\UseCases\UseCase;
use Domain\Verification\Notifications\EmailVerifiedNotification;
use Illuminate\Support\Facades\DB;

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
            condition: ! $token->user,
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
