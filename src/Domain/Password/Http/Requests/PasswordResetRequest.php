<?php

namespace Domain\Password\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Support\Token\Http\Requests\TokenRequest;

class PasswordResetRequest extends TokenRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],
            'passwordConfirm' => [
                'required',
                'string',
                'same:password',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => __('model.user.password'),
            'passwordConfirm' => __('model.user.password_confirm'),
        ];
    }

    public function getNewPassword(): string
    {
        return (string) $this->input('password');
    }
}
