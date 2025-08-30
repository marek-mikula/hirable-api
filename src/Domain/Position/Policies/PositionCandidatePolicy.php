<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;

class PositionCandidatePolicy
{
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
}
