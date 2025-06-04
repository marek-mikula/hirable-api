<?php

declare(strict_types=1);

namespace Domain\Position\Validation;

use Domain\Position\Enums\PositionOperationEnum;
use Illuminate\Validation\Validator;

class ValidateApprovalDuplicates
{
    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $operation = PositionOperationEnum::from((string) $data['operation']);

        if ($operation !== PositionOperationEnum::SEND_FOR_APPROVAL) {
            return;
        }

        $hiringManagers = collect($data['hiringManagers'] ?? []);
        $approvers = collect($data['approvers'] ?? []);

        foreach ($hiringManagers as $index => $hiringManager) {
            $exists = $approvers->some(fn (mixed $value) => $value === $hiringManager);

            if ($exists) {
                $validator->errors()->add("hiringManagers.{$index}", __('validation.after_rules.position.approval_duplicates.hiring_manager'));
            }
        }

        foreach ($approvers as $index => $approver) {
            $exists = $hiringManagers->some(fn (mixed $value) => $value === $approver);

            if ($exists) {
                $validator->errors()->add("approvers.{$index}", __('validation.after_rules.position.approval_duplicates.approver'));
            }
        }
    }
}
