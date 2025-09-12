<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Company\Enums\RoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Repositories\PositionCandidateShareRepositoryInterface;
use Domain\User\Models\User;

class PositionCandidatePolicy
{
    public function __construct(
        private readonly PositionCandidateShareRepositoryInterface $positionCandidateShareRepository,
    ) {
    }

    public function index(User $user, Position $position): bool
    {
        if ($position->state !== PositionStateEnum::OPENED) {
            return false;
        }

        /** @see PositionPolicy::show() */
        return $user->can('show', $position);
    }

    public function show(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        if ($positionCandidate->position_id !== $position->id) {
            return false;
        }

        if ($position->state !== PositionStateEnum::OPENED) {
            return false;
        }

        /** @see PositionPolicy::show() */
        return $user->can('show', $position);
    }

    public function update(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        if ($positionCandidate->position_id !== $position->id) {
            return false;
        }

        if ($position->state !== PositionStateEnum::OPENED) {
            return false;
        }

        /** @see PositionPolicy::update() */
        return $user->can('update', $position);
    }

    public function setPriority(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        return $user->company_role === RoleEnum::HIRING_MANAGER &&
            $this->positionCandidateShareRepository->isSharedWith($positionCandidate, $user);
    }
}
