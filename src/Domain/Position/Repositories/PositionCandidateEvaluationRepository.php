<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationStoreInput;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationUpdateInput;

class PositionCandidateEvaluationRepository implements PositionCandidateEvaluationRepositoryInterface
{
    public function store(PositionCandidateEvaluationStoreInput $input): PositionCandidateEvaluation
    {
        $positionCandidateEvaluation = new PositionCandidateEvaluation();
        $positionCandidateEvaluation->position_candidate_id = $input->positionCandidate->id;
        $positionCandidateEvaluation->user_id = $input->user->id;
        $positionCandidateEvaluation->evaluation = $input->evaluation;
        $positionCandidateEvaluation->result = $input->result;

        throw_if(!$positionCandidateEvaluation->save(), RepositoryException::stored(PositionCandidateEvaluation::class));

        $positionCandidateEvaluation->setRelation('positionCandidate', $input->positionCandidate);
        $positionCandidateEvaluation->setRelation('user', $input->user);

        return $positionCandidateEvaluation;
    }

    public function update(PositionCandidateEvaluation $positionCandidateEvaluation, PositionCandidateEvaluationUpdateInput $input): PositionCandidateEvaluation
    {
        $positionCandidateEvaluation->evaluation = $input->evaluation;
        $positionCandidateEvaluation->result = $input->result;

        throw_if(!$positionCandidateEvaluation->save(), RepositoryException::updated(PositionCandidateEvaluation::class));

        return $positionCandidateEvaluation;
    }
}
