<?php

declare(strict_types=1);

namespace Domain\Search\Http\Requests;

use App\Rules\Rule;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Policies\PositionPolicy;
use Illuminate\Support\Collection;

class SearchPositionUsersRequest extends SearchRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::show() */
        return $this->user()->can('show', [$this->route('position')]);
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
                Rule::enum(PositionRoleEnum::class)->only([
                    PositionRoleEnum::RECRUITER,
                    PositionRoleEnum::HIRING_MANAGER,
                    PositionRoleEnum::APPROVER,
                ]),
            ]
        ]);
    }

    public function ignoreAuth(): bool
    {
        return $this->boolean('ignoreAuth');
    }

    public function roles(): Collection
    {
        return $this->collect('roles')->map(fn (mixed $value) => PositionRoleEnum::from((string) $value));
    }
}
