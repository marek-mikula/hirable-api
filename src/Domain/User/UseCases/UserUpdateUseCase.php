<?php

declare(strict_types=1);

namespace Domain\User\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Password\Events\PasswordChanged;
use Domain\User\Models\User;
use Domain\User\Repositories\Input\UserUpdateInput;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(User $user, array $values): User
    {
        if (empty($values)) {
            return $user;
        }

        // password update
        if (array_key_exists('password', $values)) {
            // check if user entered different password
            if (Hash::check($values['password'], $user->password)) {
                throw new HttpException(responseCode: ResponseCodeEnum::CLIENT_ERROR, message: 'Password is the same.');
            }

            $user = $this->userRepository->changePassword($user, $values['password']);

            PasswordChanged::dispatch($user);

            unset($values['password']);
        }

        $input = [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'language' => $user->language,
            'prefix' => $user->prefix,
            'postfix' => $user->postfix,
            'phone' => $user->phone,
        ];

        foreach ($values as $key => $value) {
            $input[$key] = $value;
        }

        return $this->userRepository->update($user, new UserUpdateInput(...$input));
    }
}
