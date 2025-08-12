<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionCandidateStoreInput;

class PositionCandidateRepository implements PositionCandidateRepositoryInterface
{
    public function store(PositionCandidateStoreInput $input): PositionCandidate
    {
        $positionCandidate = new PositionCandidate();

        $positionCandidate->position_id = $input->position->id;
        $positionCandidate->candidate_id = $input->candidate->id;
        $positionCandidate->application_id = $input->application->id;
        $positionCandidate->step_id = $input->step->id;

        throw_if(!$positionCandidate->save(), RepositoryException::stored(PositionCandidate::class));

        $positionCandidate->setRelation('position', $input->position);
        $positionCandidate->setRelation('candidate', $input->candidate);
        $positionCandidate->setRelation('application', $input->application);
        $positionCandidate->setRelation('step', $input->step);

        return $positionCandidate;
    }

    public function setScore(PositionCandidate $positionCandidate, array $score, int $totalScore): PositionCandidate
    {
        $positionCandidate->score = $score;
        $positionCandidate->total_score = $totalScore;

        throw_if(!$positionCandidate->save(), RepositoryException::updated(PositionCandidate::class));

        return $positionCandidate;
    }

    public function setStep(
        PositionCandidate $positionCandidate,
        PositionProcessStep $positionProcessStep
    ): PositionCandidate {
        $positionCandidate->step_id = $positionProcessStep->id;

        throw_if(!$positionCandidate->save(), RepositoryException::updated(PositionCandidate::class));

        $positionCandidate->setRelation('step', $positionProcessStep);

        return $positionCandidate;
    }
}
