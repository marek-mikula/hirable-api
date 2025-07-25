<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Application\Models\Application;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Enums\PositionCandidateStepEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;

class PositionCandidateRepository implements PositionCandidateRepositoryInterface
{
    public function store(Position $position, Candidate $candidate, Application $application): PositionCandidate
    {
        $positionCandidate = new PositionCandidate();

        $positionCandidate->position_id = $position->id;
        $positionCandidate->candidate_id = $candidate->id;
        $positionCandidate->application_id = $application->id;
        $positionCandidate->state = PositionCandidateStepEnum::NEW;

        throw_if(!$positionCandidate->save(), RepositoryException::stored(PositionCandidate::class));

        $positionCandidate->setRelation('position', $position);
        $positionCandidate->setRelation('candidate', $candidate);
        $positionCandidate->setRelation('application', $application);

        return $positionCandidate;
    }

    public function setScore(PositionCandidate $positionCandidate, array $score, int $totalScore): PositionCandidate
    {
        $positionCandidate->score = $score;
        $positionCandidate->total_score = $totalScore;

        throw_if(!$positionCandidate->save(), RepositoryException::updated(PositionCandidate::class));

        return $positionCandidate;
    }
}
