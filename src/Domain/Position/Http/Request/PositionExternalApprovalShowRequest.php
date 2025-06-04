<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use Support\Token\Http\Requests\TokenRequest;

class PositionExternalApprovalShowRequest extends TokenRequest
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
