<?php

declare(strict_types=1);

namespace Support\Grid\Http\Requests;

use App\Http\Requests\AuthRequest;
use Support\Grid\Http\Requests\Data\GridColumnWidthData;

class GridSettingSetColumnWidthRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
            ],
            'width' => [
                'required',
                'integer',
            ],
        ];
    }

    public function toData(): GridColumnWidthData
    {
        return GridColumnWidthData::from([
            'key' => (string) $this->input('key'),
            'width' => (int) $this->input('width'),
        ]);
    }
}
