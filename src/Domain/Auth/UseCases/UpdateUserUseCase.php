<?php

namespace Domain\Auth\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Models\User;
use App\Repositories\User\Input\UpdateInput;
use App\Repositories\User\UserRepositoryInterface;
use App\UseCases\UseCase;
use Domain\Password\Notifications\ChangedNotification;
use Illuminate\Support\Facades\Hash;

class UpdateUserUseCase extends UseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(User $user, array $values): User
    {
        // password update
        if (array_key_exists('password', $values)) {
            // check if user entered different password
            if (Hash::check($values['password'], $user->password)) {
                throw new HttpException(responseCode: ResponseCodeEnum::CLIENT_ERROR, message: 'Password is the same.');
            }

            $user = $this->userRepository->changePassword($user, $values['password']);

            $user->notify(new ChangedNotification());

            unset($values['password']);
        }

        if (empty($values)) {
            return $user;
        }

        $input = [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'timezone' => $user->timezone,
            'notificationTechnicalMail' => $user->notification_technical_mail,
            'notificationTechnicalApp' => $user->notification_technical_app,
            'notificationMarketingMail' => $user->notification_marketing_mail,
            'notificationMarketingApp' => $user->notification_marketing_app,
            'notificationApplicationMail' => $user->notification_application_mail,
            'notificationApplicationApp' => $user->notification_application_app,
            'language' => $user->language,
            'prefix' => $user->prefix,
            'postfix' => $user->postfix,
            'phone' => $user->phone,
        ];

        foreach ($values as $key => $value) {
            $input[$key] = $value;
        }

        return $this->userRepository->update($user, UpdateInput::from($input));
    }
}
