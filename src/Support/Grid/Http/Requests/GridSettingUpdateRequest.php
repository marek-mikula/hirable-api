<?php

declare(strict_types=1);

namespace Support\Grid\Http\Requests;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Support\Grid\Enums\PerPageEnum;
use Support\Grid\Http\Requests\Data\GridColumnSettingData;
use Support\Grid\Http\Requests\Data\GridSettingData;

class GridSettingUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'perPage' => [
                'required',
                'integer',
                Rule::enum(PerPageEnum::class),
            ],
            'stickyHeader' => [
                'boolean',
            ],
            'stickyFooter' => [
                'boolean',
            ],
            'columns' => [
                'required',
                'array',
            ],
            'columns.*' => [
                'required',
                'array:key,enabled',
            ],
            'columns.*.key' => [
                'required',
                'string',
            ],
            'columns.*.enabled' => [
                'boolean',
            ],
        ];
    }

    public function toData(): GridSettingData
    {
        return GridSettingData::from([
            'perPage' => $this->enum('perPage', PerPageEnum::class),
            'stickyHeader' => $this->boolean('stickyHeader'),
            'stickyFooter' => $this->boolean('stickyFooter'),
            'columns' => $this->collect('columns')->map(static fn (mixed $column) => GridColumnSettingData::from([
                'key' => (string) Arr::get($column, 'key'),
                'enabled' => (bool) Arr::get($column, 'enabled'),
            ]))->all(),
        ]);
    }
}
