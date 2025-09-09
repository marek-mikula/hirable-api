<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\User\Models\User;

class PositionCandidateEvaluationPolicy
{
    public function index(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        /** @see PositionCandidatePolicy::index() */
        return $user->can('index', [PositionCandidate::class, $position]);
    }

    public function store(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        /** @see PositionCandidatePolicy::update() */
        return $user->can('update', [$positionCandidate, $position]);
    }

    public function delete(User $user, PositionCandidateEvaluation $positionCandidateEvaluation, PositionCandidate $positionCandidate, Position $position): bool
    {
        /** @see PositionCandidatePolicy::update() */
        return $user->can('update', [$positionCandidate, $position]);
    }
}
