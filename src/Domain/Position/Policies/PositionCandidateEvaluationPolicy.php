<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Company\Enums\RoleEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\PositionCandidateShareRepositoryInterface;
use Domain\User\Models\User;

class PositionCandidateEvaluationPolicy
{
    public function __construct(
        private readonly PositionCandidateShareRepositoryInterface $positionCandidateShareRepository,
    ) {
    }

    public function index(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        /** @see PositionCandidatePolicy::index() */
        return $user->can('index', [PositionCandidate::class, $position]);
    }

    public function store(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        return $user->company_role === RoleEnum::HIRING_MANAGER && $this->positionCandidateShareRepository->isSharedWith($positionCandidate, $user);
    }

    public function request(User $user, PositionCandidate $positionCandidate, Position $position): bool
    {
        /** @see PositionCandidatePolicy::update() */
        return $user->can('update', [$positionCandidate, $position]);
    }

    public function update(User $user, PositionCandidateEvaluation $positionCandidateEvaluation, PositionCandidate $positionCandidate, Position $position): bool
    {
        if ($positionCandidate->position_id !== $position->id) {
            return false;
        }

        if ($positionCandidateEvaluation->position_candidate_id !== $positionCandidate->id) {
            return false;
        }

        return $user->company_role === RoleEnum::HIRING_MANAGER && $positionCandidateEvaluation->user_id === $user->id;
    }

    public function delete(User $user, PositionCandidateEvaluation $positionCandidateEvaluation, PositionCandidate $positionCandidate, Position $position): bool
    {
        if ($positionCandidate->position_id !== $position->id) {
            return false;
        }

        if ($positionCandidateEvaluation->position_candidate_id !== $positionCandidate->id) {
            return false;
        }

        if ($user->company_role === RoleEnum::HIRING_MANAGER) {
            return $positionCandidateEvaluation->creator_id === $user->id && $positionCandidateEvaluation->user_id === $user->id;
        }

        return $positionCandidateEvaluation->creator_id === $user->id;
    }
}
