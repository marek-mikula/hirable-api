<?php

declare(strict_types=1);

namespace Domain\Position\Validation;

use Domain\Position\Enums\PositionOperationEnum;
use Illuminate\Validation\Validator;

class ValidateApprovalRequiredFields
{
    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $operation = PositionOperationEnum::from((string) $data['operation']);

        if ($operation !== PositionOperationEnum::SEND_FOR_APPROVAL) {
            return;
        }

        $hiringManagers = $data['hiringManagers'] ?? [];
        $approvers = $data['approvers'] ?? [];
        $externalApprovers = $data['externalApprovers'] ?? [];

        if (!empty($hiringManagers) || !empty($approvers) || !empty($externalApprovers)) {
            return;
        }

        $validator->errors()->add('hiringManagers', __('validation.required'));
        $validator->errors()->add('approvers', __('validation.required'));
        $validator->errors()->add('externalApprovers', __('validation.required'));
    }
}
