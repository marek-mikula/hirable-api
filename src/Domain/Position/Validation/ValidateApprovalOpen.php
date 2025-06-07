<?php

declare(strict_types=1);

namespace Domain\Position\Validation;

use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Illuminate\Validation\Validator;

class ValidateApprovalOpen
{
    public function __construct(private readonly ?Position $position)
    {
    }

    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $operation = PositionOperationEnum::from((string) $data['operation']);

        if ($operation !== PositionOperationEnum::OPEN) {
            return;
        }

        if ($this->position?->state === PositionStateEnum::APPROVAL_APPROVED) {
            return;
        }

        $hiringManagers = $data['hiringManagers'] ?? [];
        $approvers = $data['approvers'] ?? [];
        $externalApprovers = $data['externalApprovers'] ?? [];

        if (!empty($hiringManagers)) {
            $validator->errors()->add('hiringManagers', __('validation.after_rules.position.approval_open.hiring_manager'));
        }

        if (!empty($approvers)) {
            $validator->errors()->add('approvers', __('validation.after_rules.position.approval_open.approver'));
        }

        if (!empty($externalApprovers)) {
            $validator->errors()->add('externalApprovers', __('validation.after_rules.position.approval_open.external_approver'));
        }
    }
}
