<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationStoreInput;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationUpdateInput;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface PositionCandidateEvaluationRepositoryInterface
{
    /**
     * @param string[] $with
     * @return Collection<PositionCandidateEvaluation>
     */
    public function index(PositionCandidate $positionCandidate, array $with = []): Collection;

    public function store(PositionCandidateEvaluationStoreInput $input): PositionCandidateEvaluation;

    public function update(PositionCandidateEvaluation $positionCandidateEvaluation, PositionCandidateEvaluationUpdateInput $input): PositionCandidateEvaluation;

    public function delete(PositionCandidateEvaluation $positionCandidateEvaluation): void;

    public function evaluationExists(PositionCandidate $positionCandidate, User $user): bool;
}
