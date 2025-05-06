<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Models\Company;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\Unique;

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
                new In([
                    'name',
                    'email',
                    'idNumber',
                    'website',
                    'environment',
                    'benefits',
                ]),
            ],
            'name' => [
                new ExcludeIf(!in_array('name', $keys)),
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                new ExcludeIf(!in_array('email', $keys)),
                'required',
                'string',
                'max:255',
                'email',
                (new Unique(Company::class, 'email'))->ignore($user->company_id),
            ],
            'idNumber' => [
                new ExcludeIf(!in_array('idNumber', $keys)),
                'required',
                'string',
                'max:255',
                (new Unique(Company::class, 'id_number'))->ignore($user->company_id),
            ],
            'website' => [
                new ExcludeIf(!in_array('website', $keys)),
                'nullable',
                'string',
                'url',
                'max:255',
            ],
            'environment' => [
                new ExcludeIf(!in_array('environment', $keys)),
                'nullable',
                'string',
                'max:1000',
            ],
            'benefits' => [
                new ExcludeIf(!in_array('benefits', $keys)),
                'nullable',
                'array',
            ],
            'benefits.*' => [
                new ExcludeIf(!in_array('benefits', $keys)),
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
