<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Policies\CompanyPolicy;
use Support\Grid\Concerns\AsGridRequest;

class CompanyUserIndexRequest extends AuthRequest
{
    use AsGridRequest;

    public function authorize(): bool
    {
        /** @see CompanyPolicy::showUsers() */
        return $this->user()->can('showUsers', $this->route('company'));
    }

    public function rules(): array
    {
        return [];
    }
}
