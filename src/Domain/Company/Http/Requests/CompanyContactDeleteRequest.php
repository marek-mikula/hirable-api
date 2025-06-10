<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;

class CompanyContactDeleteRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CompanyPolicy::deleteContact() */
        return $this->user()->can('deleteContact', [$this->route('company'), $this->route('contact')]);
    }

    public function rules(): array
    {
        return [];
    }
}
