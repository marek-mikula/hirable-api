<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Enums\LanguageEnum;
use App\Http\Requests\AuthRequest;
use Domain\Company\Http\Requests\Data\ContactData;
use Domain\Company\Models\CompanyContact;
use Illuminate\Validation\Rule;

class CompanyContactStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CompanyPolicy::storeContact() */
        return $this->user()->can('storeContact', $this->route('company'));
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'language' => [
                'required',
                'string',
                Rule::enum(LanguageEnum::class),
            ],
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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(CompanyContact::class)->where('company_id', $user->company_id),
            ],
            'note' => [
                'nullable',
                'string',
                'max:300',
            ],
            'companyName' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function toData(): ContactData
    {
        return ContactData::from([
           'language' => LanguageEnum::from((string) $this->input('language')),
           'firstname' => (string) $this->input('firstname'),
           'lastname' => (string) $this->input('lastname'),
           'email' => (string) $this->input('email'),
           'note' => $this->filled('note') ? (string) $this->input('note') : null,
           'companyName' => $this->filled('companyName') ? (string) $this->input('companyName') : null,
        ]);
    }
}
