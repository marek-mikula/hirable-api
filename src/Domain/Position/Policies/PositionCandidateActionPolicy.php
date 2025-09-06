<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\User\Models\User;

class PositionCandidateActionPolicy
{
    public function store(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        /** @see PositionCandidatePolicy::update() */
        return $user->can('update', [$positionCandidate, $position]);
    }

    public function show(User $user, PositionCandidateAction $positionCandidateAction, PositionCandidate $positionCandidate, Position $position): bool
    {
        if ($positionCandidateAction->position_candidate_id !== $positionCandidate->id) {
            return false;
        }

        /** @see PositionCandidatePolicy::show() */
        return $user->can('show', [$positionCandidate, $position]);
    }

    public function update(User $user, PositionCandidateAction $positionCandidateAction, PositionCandidate $positionCandidate, Position $position): bool
    {
        if ($positionCandidateAction->position_candidate_id !== $positionCandidate->id) {
            return false;
        }

        if ($positionCandidateAction->user_id !== $user->id) {
            return false;
        }

        /** @see PositionCandidatePolicy::show() */
        return $user->can('update', [$positionCandidate, $position]);
    }
}
