<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Policies\CompanyPolicy;

class CompanyShowRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CompanyPolicy::show() */
        return $this->user()->can('show', $this->route('company'));
    }

    public function rules(): array
    {
        return [];
    }
}
