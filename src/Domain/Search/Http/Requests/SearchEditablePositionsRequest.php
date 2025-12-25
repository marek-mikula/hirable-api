<?php

declare(strict_types=1);

namespace Domain\Search\Http\Requests;

use App\Rules\Rule;
use Domain\Position\Enums\PositionStateEnum;
use Illuminate\Support\Collection;

class SearchEditablePositionsRequest extends SearchRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'states' => [
                'array',
            ],
            'states.*' => [
                'required',
                'string',
                Rule::enum(PositionStateEnum::class),
            ]
        ]);
    }

    public function states(): Collection
    {
        return $this->collect('states')->map(fn (mixed $value) => PositionStateEnum::from((string) $value));
    }
}
