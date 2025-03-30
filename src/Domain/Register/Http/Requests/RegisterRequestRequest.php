<?php

namespace Domain\Register\Http\Requests;

use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Unique;

class RegisterRequestRequest extends Request
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
                new Unique(User::class, 'email'),
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
        return (string) $this->string('email')->lower();
    }
}
