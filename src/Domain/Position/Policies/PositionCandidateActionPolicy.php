<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;

class PositionCandidateActionPolicy
{
    public function store(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        /** @see PositionCandidatePolicy::update() */
        return $user->can('update', [$positionCandidate, $position]);
    }
}
