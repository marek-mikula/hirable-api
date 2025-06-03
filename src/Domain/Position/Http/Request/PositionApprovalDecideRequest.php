<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Http\Request\Data\PositionApprovalUpdateData;
use Illuminate\Validation\Rule;

class PositionApprovalDecideRequest extends AuthRequest
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

    public function toData(): PositionApprovalUpdateData
    {
        $state = PositionApprovalStateEnum::from((string) $this->input('state'));

        return PositionApprovalUpdateData::from([
            'state' => $state,
            'note' => $this->filled('note') || $state === PositionApprovalStateEnum::REJECTED ? (string) $this->input('note') : null,
        ]);
    }
}
