<?php

declare(strict_types=1);

namespace Domain\Register\Http\Requests;

use Domain\Company\Models\Company;
use Domain\Register\Http\Requests\Data\CompanyData;
use Domain\Register\Http\Requests\Data\RegisterData;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Requests\TokenRequest;

class RegisterRegisterRequest extends TokenRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $token = $this->getToken();

        $userRules = [
            'firstname' => [
                'required',
                'string',
                'max:255',
            ],
            'lastname' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],
            'passwordConfirm' => [
                'required',
                'string',
                'same:password',
            ],
        ];

        $companyRules = [
            'companyName' => [
                'required',
                'string',
                'max:255',
            ],
            'companyEmail' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Company::class, 'email'),
            ],
            'companyIdNumber' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Company::class, 'id_number'),
            ],
            'companyWebsite' => [
                'nullable',
                'string',
                'url',
                'max:255',
            ],
        ];

        if ($token->type === TokenTypeEnum::INVITATION) {
            return $userRules;
        }

        return array_merge($userRules, $companyRules);
    }

    public function attributes(): array
    {
        return [
            'firstname' => __('model.common.firstname'),
            'lastname' => __('model.common.lastname'),
            'password' => __('model.user.password'),
            'passwordConfirm' => __('model.user.password_confirm'),
            'companyName' => __('model.company.name'),
            'companyEmail' => __('model.company.email'),
            'companyIdNumber' => __('model.company.id_number'),
            'companyWebsite' => __('model.company.website'),
        ];
    }

    public function toData(): RegisterData
    {
        $token = $this->getToken();

        if ($token->type === TokenTypeEnum::INVITATION) {
            return once(fn () => RegisterData::from([
                'firstname' => (string) $this->input('firstname'),
                'lastname' => (string) $this->input('lastname'),
                'password' => (string) $this->input('password'),
                'agreementIp' => $this->ip() ?? 'N/A',
                'agreementAcceptedAt' => now(),
                'company' => null,
            ]));
        }

        return once(fn () => RegisterData::from([
            'firstname' => (string) $this->input('firstname'),
            'lastname' => (string) $this->input('lastname'),
            'password' => (string) $this->input('password'),
            'agreementIp' => $this->ip() ?? 'N/A',
            'agreementAcceptedAt' => now(),
            'company' => CompanyData::from([
                'name' => (string) $this->input('companyName'),
                'email' => (string) $this->input('companyEmail'),
                'idNumber' => (string) $this->input('companyIdNumber'),
                'website' => $this->filled('companyWebsite') ? (string) $this->input('companyWebsite') : null,
            ]),
        ]));
    }
}
