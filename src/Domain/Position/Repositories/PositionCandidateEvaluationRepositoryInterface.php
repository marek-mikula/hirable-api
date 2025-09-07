<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationStoreInput;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationUpdateInput;

interface PositionCandidateEvaluationRepositoryInterface
{
    public function store(PositionCandidateEvaluationStoreInput $input): PositionCandidateEvaluation;

    public function update(PositionCandidateEvaluation $positionCandidateEvaluation, PositionCandidateEvaluationUpdateInput $input): PositionCandidateEvaluation;
}
