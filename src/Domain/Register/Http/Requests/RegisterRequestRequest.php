<?php

declare(strict_types=1);

namespace Domain\Register\Http\Requests;

use App\Http\Requests\Request;
use Domain\User\Models\User;
use App\Rules\Rule;

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
                'max:255',
                Rule::unique(User::class, 'email'),
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
        return $this->string('email')->lower()->toString();
    }
}
