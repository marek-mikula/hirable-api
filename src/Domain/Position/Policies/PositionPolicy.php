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

        if ($position->state === PositionStateEnum::APPROVAL_PENDING) {
            /** @var PositionApprovalRepositoryInterface $positionApprovalRepository */
            $positionApprovalRepository = app(PositionApprovalRepositoryInterface::class);

            return $positionApprovalRepository->hasModelAsApproverInState($position, $user, PositionApprovalStateEnum::PENDING);
        }

        return false;
    }

    public function update(User $user, Position $position): bool
    {
        $notInStates = [
            PositionStateEnum::APPROVAL_PENDING,
            PositionStateEnum::CLOSED,
            PositionStateEnum::CANCELED,
        ];

        return !in_array($position->state, $notInStates) && $user->id === $position->user_id;
    }
}
