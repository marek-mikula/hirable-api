<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Concerns\FailsWithStatus;

class CompanyContactSuggestCompaniesRequest extends AuthRequest
{
    use FailsWithStatus;

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
