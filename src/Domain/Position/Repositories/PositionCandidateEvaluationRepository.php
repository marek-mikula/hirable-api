<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationStoreInput;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationUpdateInput;
use Illuminate\Database\Eloquent\Collection;

class PositionCandidateEvaluationRepository implements PositionCandidateEvaluationRepositoryInterface
{
    public function index(PositionCandidate $positionCandidate, array $with = []): Collection
    {
        return PositionCandidateEvaluation::query()
            ->with($with)
            ->where('position_candidate_id', $positionCandidate->id)
            ->get();
    }

    public function store(PositionCandidateEvaluationStoreInput $input): PositionCandidateEvaluation
    {
        $positionCandidateEvaluation = new PositionCandidateEvaluation();
        $positionCandidateEvaluation->creator_id = $input->creator->id;
        $positionCandidateEvaluation->position_candidate_id = $input->positionCandidate->id;
        $positionCandidateEvaluation->user_id = $input->user->id;
        $positionCandidateEvaluation->state = $input->state;
        $positionCandidateEvaluation->evaluation = $input->evaluation;
        $positionCandidateEvaluation->stars = $input->stars;
        $positionCandidateEvaluation->fill_until = $input->fillUntil;

        throw_if(!$positionCandidateEvaluation->save(), RepositoryException::stored(PositionCandidateEvaluation::class));

        $positionCandidateEvaluation->setRelation('creator', $input->creator);
        $positionCandidateEvaluation->setRelation('positionCandidate', $input->positionCandidate);
        $positionCandidateEvaluation->setRelation('user', $input->user);

        return $positionCandidateEvaluation;
    }

    public function update(PositionCandidateEvaluation $positionCandidateEvaluation, PositionCandidateEvaluationUpdateInput $input): PositionCandidateEvaluation
    {
        $positionCandidateEvaluation->state = $input->state;
        $positionCandidateEvaluation->evaluation = $input->evaluation;
        $positionCandidateEvaluation->stars = $input->stars;

        throw_if(!$positionCandidateEvaluation->save(), RepositoryException::updated(PositionCandidateEvaluation::class));

        return $positionCandidateEvaluation;
    }

    public function delete(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        throw_if(!$positionCandidateEvaluation->delete(), RepositoryException::updated(PositionCandidateEvaluation::class));
    }
}
