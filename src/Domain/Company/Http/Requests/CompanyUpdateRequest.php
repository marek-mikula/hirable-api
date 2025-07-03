<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Models\Company;
use App\Rules\Rule;

class CompanyUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CompanyPolicy::update() */
        return $this->user()->can('update', $this->route('company'));
    }

    public function rules(): array
    {
        $user = $this->user();

        $keys = is_array($keys = $this->input('keys', [])) ? $keys : [];

        return [
            // keys array tells us, which attributes
            // do we want to update
            'keys' => [
                'required',
                'array',
            ],
            'keys.*' => [
                'required',
                'string',
                Rule::in([
                    'name',
                    'email',
                    'idNumber',
                    'website',
                ])
            ],
            'name' => [
                Rule::excludeIf(!in_array('name', $keys)).
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                Rule::excludeIf(!in_array('email', $keys)).
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique(Company::class, 'email')->ignore($user->company_id),
            ],
            'idNumber' => [
                Rule::excludeIf(!in_array('idNumber', $keys)),
                'required',
                'string',
                'max:255',
                Rule::unique(Company::class, 'id_number')->ignore($user->company_id),
            ],
            'website' => [
                Rule::excludeIf(!in_array('website', $keys)),
                'nullable',
                'string',
                'url',
                'max:255',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('model.company.name'),
            'email' => __('model.company.email'),
            'idNumber' => __('model.company.id_number'),
            'website' => __('model.company.website'),
        ];
    }

    public function getValues(): array
    {
        $data = [];

        foreach ($this->array('keys') as $key) {
            $data[$key] = match ($key) {
                'name', 'email', 'idNumber' => (string) $this->input($key),
                'website' => $this->filled($key) ? (string) $this->input($key) : null,
            };
        }

        return $data;
    }
}
