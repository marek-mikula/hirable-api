<?php

declare(strict_types=1);

namespace Domain\Search\Http\Requests;

use App\Rules\Rule;
use Domain\Company\Enums\RoleEnum;
use Illuminate\Support\Collection;

class SearchCompanyUsersRequest extends SearchRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'ignoreAuth' => [
                'nullable',
                'boolean',
            ],
            'roles' => [
                'array',
            ],
            'roles.*' => [
                'required',
                'string',
                Rule::enum(RoleEnum::class),
            ]
        ]);
    }

    public function ignoreAuth(): bool
    {
        return $this->boolean('ignoreAuth');
    }

    public function roles(): Collection
    {
        return $this->collect('roles')->map(fn (mixed $value) => RoleEnum::from((string) $value));
    }
}
