<?php

declare(strict_types=1);

namespace Domain\Auth\Http\Requests;

use App\Http\Requests\AuthRequest;

class AuthLogoutRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
