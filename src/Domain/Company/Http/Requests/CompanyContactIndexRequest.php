<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Policies\CompanyPolicy;
use Support\Grid\Traits\AsGridRequest;

class CompanyContactIndexRequest extends AuthRequest
{
    use AsGridRequest;

    public function authorize(): bool
    {
        /** @see CompanyPolicy::showContacts() */
        return $this->user()->can('showContacts', $this->route('company'));
    }

    public function rules(): array
    {
        return [];
    }
}
