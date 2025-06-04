<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;

class PositionApprovalPolicy
{
    public function decide(User $user, PositionApproval $approval): bool
    {
        if ($approval->state !== PositionApprovalStateEnum::PENDING) {
            return false;
        }

        $approval->loadMissing(['modelHasPosition', 'modelHasPosition.model']);

        return $approval->modelHasPosition->model->is($user);
    }
}
