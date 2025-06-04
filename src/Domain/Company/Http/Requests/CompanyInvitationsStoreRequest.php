<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Http\Requests\Data\InvitationStoreData;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyInvitationsStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'string',
                Rule::enum(RoleEnum::class),
            ],
            'email' => [
                'required',
                'email',
            ],
        ];
    }

    public function toData(): InvitationStoreData
    {
        return InvitationStoreData::from([
            'role' => $this->enum('role', RoleEnum::class),
            'email' => Str::lower((string) $this->input('email')),
        ]);
    }
}
