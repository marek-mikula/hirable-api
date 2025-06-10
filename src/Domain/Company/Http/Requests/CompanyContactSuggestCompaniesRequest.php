<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;

class CompanyContactSuggestCompaniesRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CompanyPolicy::showContacts() */
        return $this->user()->can('showContacts', $this->route('company'));
    }

    public function rules(): array
    {
        return [
            'q' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function getQuery(): ?string
    {
        return $this->filled('q') ? (string) $this->input('q') : null;
    }
}
