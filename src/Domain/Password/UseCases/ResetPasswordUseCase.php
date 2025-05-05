<?php

declare(strict_types=1);

namespace Domain\Password\UseCases;

use App\UseCases\UseCase;
use Domain\Password\Notifications\ChangedNotification;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Support\Token\Models\Token;
use Support\Token\Repositories\TokenRepositoryInterface;

class ResetPasswordUseCase extends UseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(Token $token, string $newPassword): void
    {
        $user = $token->loadMissing('user')->user;

        DB::transaction(function () use (
            $user,
            $token,
            $newPassword,
        ): void {
            $this->userRepository->changePassword($user, $newPassword);

            $this->tokenRepository->markUsed($token);

            $user->notify(new ChangedNotification());
        }, attempts: 5);
    }
}
