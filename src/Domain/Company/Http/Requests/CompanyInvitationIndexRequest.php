<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Policies\CompanyPolicy;
use Support\Grid\Traits\AsGridRequest;

class CompanyInvitationIndexRequest extends AuthRequest
{
    use AsGridRequest;

    public function authorize(): bool
    {
        /** @see CompanyPolicy::showInvitations() */
        return $this->user()->can('showInvitations', $this->route('company'));
    }

    public function rules(): array
    {
        return [];
    }
}
