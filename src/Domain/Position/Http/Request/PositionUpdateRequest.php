<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use Domain\Position\Enums\PositionOperationEnum;
use Illuminate\Validation\Rules\In;

class PositionUpdateRequest extends PositionStoreRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'operation' => [
                'required',
                'string',
                new In([
                    PositionOperationEnum::SAVE->value,
                    PositionOperationEnum::OPEN->value,
                    PositionOperationEnum::SEND_FOR_APPROVAL->value,
                ]),
            ],
        ]);
    }
}
