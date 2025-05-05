<?php

declare(strict_types=1);

namespace Domain\Auth\Services;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Http\Requests\AuthRequest;
use Domain\Auth\Http\Requests\Data\LoginData;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function login(LoginData $data): User
    {
        $user = $this->userRepository->findByEmail($data->email);

        // user not found or passwords do not match
        if (!$user || !Hash::check($data->password, $user->password)) {
            throw new HttpException(responseCode: ResponseCodeEnum::INVALID_CREDENTIALS);
        }

        // user needs to verify his email
        if (!$user->is_email_verified) {
            throw new HttpException(responseCode: ResponseCodeEnum::EMAIL_VERIFICATION_NEEDED);
        }

        $this->loginWithModel(user: $user, rememberMe: $data->rememberMe);

        return $user;
    }

    public function loginWithModel(User $user, bool $rememberMe = false): void
    {
        auth('api')->login(user: $user, remember: $rememberMe);
    }

    public function logout(AuthRequest $request): void
    {
        auth('api')->logout();

        // forget Sanctum user because in tests
        // the state of the Sanctum guard stayed
        // the same and guest routes were acting
        // as if the user was still logged in
        auth('sanctum')->forgetUser();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
