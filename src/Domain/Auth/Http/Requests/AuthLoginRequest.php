<?php

namespace Domain\Auth\Http\Requests;

use App\Http\Requests\Request;
use Domain\Auth\Http\Requests\Data\LoginData;

class AuthLoginRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
            'rememberMe' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => __('model.common.email'),
            'password' => __('model.user.password'),
            'rememberMe' => __('form.login.fields.remember_me'),
        ];
    }

    public function toData(): LoginData
    {
        return once(fn () => LoginData::from([
            'email' => (string) $this->input('email'),
            'password' => (string) $this->input('password'),
            'rememberMe' => $this->boolean('rememberMe'),
        ]));
    }
}
