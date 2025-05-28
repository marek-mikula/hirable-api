<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Http\Requests\Data\ContactStoreData;
use Domain\Company\Models\CompanyContact;
use Illuminate\Validation\Rules\Unique;

class CompanyContactStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
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
                (new Unique(CompanyContact::class))->where('company_id', $user->company_id),
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

    public function toData(): ContactStoreData
    {
        return ContactStoreData::from([
           'firstname' => (string) $this->input('firstname'),
           'lastname' => (string) $this->input('lastname'),
           'email' => (string) $this->input('email'),
           'note' => $this->filled('note') ? (string) $this->input('note') : null,
           'companyName' => $this->filled('companyName') ? (string) $this->input('companyName') : null,
        ]);
    }
}
