<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;

class PositionApprovalPolicy
{
    public function decide(User $user, PositionApproval $approval): bool
    {
        $model = $approval->loadMissing('modelHasPosition')->modelHasPosition;

        return $model->is($user);
    }
}
