<?php

declare(strict_types=1);

namespace Domain\Position\Validation;

use Domain\Position\Enums\PositionOperationEnum;
use Illuminate\Validation\Validator;

class ValidateApprovalOpen
{
    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $operation = PositionOperationEnum::from((string) $data['operation']);

        if ($operation !== PositionOperationEnum::OPEN) {
            return;
        }

        $approvers = $data['approvers'] ?? [];
        $externalApprovers = $data['externalApprovers'] ?? [];

        if (!empty($approvers)) {
            $validator->errors()->add('approvers', __('validation.after_rules.position.approval_open'));
        }

        if (!empty($externalApprovers)) {
            $validator->errors()->add('externalApprovers', __('validation.after_rules.position.approval_open'));
        }
    }
}
