<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Company\Enums\RoleEnum;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\User\Models\User;

class PositionPolicy
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
    ) {
    }

    public function store(User $user): bool
    {
        return in_array($user->company_role, [
            RoleEnum::ADMIN,
            RoleEnum::RECRUITER,
        ]);
    }

    public function show(User $user, Position $position): bool
    {
        if ($user->company_id !== $position->company_id) {
            return false;
        }

        // user is the owner
        if ($user->id === $position->user_id) {
            return true;
        }

        $rolesOnPosition = [
            PositionRoleEnum::HIRING_MANAGER,
            PositionRoleEnum::RECRUITER,
        ];

        // user is hiring manager or recruiter on position
        // and position has been opened
        if (
            in_array($position->state, PositionStateEnum::getAfterOpenedStates()) &&
            $this->modelHasPositionRepository->hasModelRoleOnPosition($user, $position, ...$rolesOnPosition)
        ) {
            return true;
        }

        // user is approver in pending state
        // and position is in approval pending state
        if (
            $position->state === PositionStateEnum::APPROVAL_PENDING &&
            $this->positionApprovalRepository->hasModelAsApproverOnPositionInState(
                position: $position,
                model: $user,
                state: PositionApprovalStateEnum::PENDING
            )
        ) {
            return true;
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

        return $user->company_id === $position->company_id && $user->id === $position->user_id && !in_array($position->state, $notInStates);
    }

    public function delete(User $user, Position $position): bool
    {
        $notInStates = [
            PositionStateEnum::OPENED,
            PositionStateEnum::CLOSED,
            PositionStateEnum::CANCELED,
        ];

        return $user->company_id === $position->company_id && $user->id === $position->user_id && !in_array($position->state, $notInStates);
    }

    public function duplicate(User $user, Position $position): bool
    {
        return $this->store($user) && $this->show($user, $position);
    }

    public function cancelApproval(User $user, Position $position): bool
    {
        return $user->company_id === $position->company_id && $user->id === $position->user_id;
    }
}
