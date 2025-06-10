<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Policies\CompanyPolicy;

class CompanyInvitationDeleteRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CompanyPolicy::deleteInvitation() */
        return $this->user()->can('deleteInvitation', [$this->route('company'), $this->route('invitation')]);
    }

    public function rules(): array
    {
        return [];
    }
}
