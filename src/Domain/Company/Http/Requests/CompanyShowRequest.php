<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;

class CompanyShowRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
