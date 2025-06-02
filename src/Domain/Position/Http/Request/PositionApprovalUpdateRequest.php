<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Http\Request\Data\PositionApprovalUpdateData;
use Illuminate\Validation\Rule;

class PositionApprovalUpdateRequest extends AuthRequest
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
                'string',
                'max:300',
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
        return PositionApprovalUpdateData::from([
            'note' => $this->filled('note') ? (string) $this->input('note') : null,
            'state' => PositionApprovalStateEnum::from((string) $this->input('state')),
        ]);
    }
}
