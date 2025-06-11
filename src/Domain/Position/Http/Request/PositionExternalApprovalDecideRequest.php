<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Http\Request\Data\PositionApprovalDecideData;
use Illuminate\Validation\Rule;
use Support\Token\Http\Requests\TokenRequest;

class PositionExternalApprovalDecideRequest extends TokenRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => [
                'nullable',
                Rule::requiredIf($this->input('state') === PositionApprovalStateEnum::REJECTED),
                'string',
                'max:500',
            ],
            'state' => [
                'required',
                Rule::in([
                    PositionApprovalStateEnum::APPROVED->value,
                    PositionApprovalStateEnum::REJECTED->value,
                ]),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'note' => __('model.common.note'),
        ];
    }

    public function toData(): PositionApprovalDecideData
    {
        $state = PositionApprovalStateEnum::from((string) $this->input('state'));

        return PositionApprovalDecideData::from([
            'state' => $state,
            'note' => $this->filled('note') || $state === PositionApprovalStateEnum::REJECTED ? (string) $this->input('note') : null,
        ]);
    }
}
