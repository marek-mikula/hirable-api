<?php

namespace Domain\Password\Http\Requests;

use App\Http\Requests\Request;

class PasswordRequestResetRequest extends Request
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
                'string',
                'email',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => __('model.common.email'),
        ];
    }

    public function getEmail(): string
    {
        return (string) $this->input('email');
    }
}
