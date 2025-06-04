<?php

declare(strict_types=1);

namespace Domain\Position\Validation;

use Domain\Position\Enums\PositionOperationEnum;
use Domain\User\Models\User;
use Illuminate\Validation\Validator;

class ValidateApprovalSelf
{
    public function __construct(private readonly User $user)
    {
    }

    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $operation = PositionOperationEnum::from((string) $data['operation']);

        if ($operation !== PositionOperationEnum::SEND_FOR_APPROVAL) {
            return;
        }

        $hiringManagers = collect($data['hiringManagers'] ?? []);
        $approvers = collect($data['approvers'] ?? []);

        $asHm = $hiringManagers->some(fn (mixed $value) => $value === $this->user->id);
        $asApprover = $approvers->some(fn (mixed $value) => $value === $this->user->id);

        if ($asHm) {
            $validator->errors()->add('hiringManagers', __('validation.after_rules.position.approval_self.hiring_manager'));
        }

        if ($asApprover) {
            $validator->errors()->add('approvers', __('validation.after_rules.position.approval_self.approver'));
        }
    }
}
