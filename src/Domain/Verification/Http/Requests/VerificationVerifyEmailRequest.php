<?php

declare(strict_types=1);

namespace Domain\Verification\Http\Requests;

use Support\Token\Http\Requests\TokenRequest;

class VerificationVerifyEmailRequest extends TokenRequest
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
