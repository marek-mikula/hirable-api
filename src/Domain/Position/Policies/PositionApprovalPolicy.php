<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;

class PositionApprovalPolicy
{
    public function decide(User $user, PositionApproval $approval, Position $position): bool
    {
        if ($position->id !== $approval->position_id) {
            return false;
        }

        if ($approval->state !== PositionApprovalStateEnum::PENDING) {
            return false;
        }

        /** @see PositionPolicy::show() */
        if (!$user->can('show', $position)) {
            return false;
        }

        $approval->loadMissing(['modelHasPosition', 'modelHasPosition.model']);

        return $approval->modelHasPosition->model->is($user);
    }
}
