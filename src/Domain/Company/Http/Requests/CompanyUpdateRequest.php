<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Models\Company;
use Illuminate\Validation\Rule;

class CompanyUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
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
                'min:1',
            ],
            'keys.*' => [
                'required',
                'string',
                Rule::in([
                    'name',
                    'email',
                    'idNumber',
                    'website',
                    'environment',
                    'benefits',
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
            'environment' => [
                Rule::excludeIf(!in_array('environment', $keys)),
                'nullable',
                'string',
                'max:1000',
            ],
            'benefits' => [
                Rule::excludeIf(!in_array('benefits', $keys)),
                'nullable',
                'array',
            ],
            'benefits.*' => [
                Rule::excludeIf(!in_array('benefits', $keys)),
                'required',
                'string',
            ],
        ];
    }

    public function getValues(): array
    {
        $data = [];

        foreach ($this->input('keys', []) as $key) {
            $data[$key] = match ($key) {
                'name', 'email', 'idNumber', 'environment' => (string) $this->input($key),
                'website' => $this->filled($key) ? (string) $this->input($key) : null,
                'benefits' => $this->collect('benefits')->all(),
            };
        }

        return $data;
    }
}
