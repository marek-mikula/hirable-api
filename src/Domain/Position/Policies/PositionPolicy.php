<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\User\Models\User;

class PositionPolicy
{
    public function cancelApproval(User $user, Position $position): bool
    {
        return $user->id === $position->user_id;
    }

    public function show(User $user, Position $position): bool
    {
        if ($user->id === $position->user_id) {
            return true;
        }

        /** @var ModelHasPositionRepositoryInterface $modelHasPositionRepository */
        $modelHasPositionRepository = app(ModelHasPositionRepositoryInterface::class);

        if ($modelHasPositionRepository->hasModelRoleOnPosition($user, $position, PositionRoleEnum::HIRING_MANAGER)) {
            return true;
        }

        /** @var PositionApprovalRepositoryInterface $positionApprovalRepository */
        $positionApprovalRepository = app(PositionApprovalRepositoryInterface::class);

        return $positionApprovalRepository->hasModelAsApproverInState($position, $user, PositionApprovalStateEnum::PENDING);
    }

    public function update(User $user, Position $position): bool
    {
        if ($position->approval_state === PositionApprovalStateEnum::PENDING) {
            return false;
        }

        if ($position->state !== PositionStateEnum::DRAFT) {
            return false;
        }

        return $user->id === $position->user_id;
    }
}
