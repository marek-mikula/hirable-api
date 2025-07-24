<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Application\Models\Application;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;

interface PositionCandidateRepositoryInterface
{
    public function store(Position $position, Candidate $candidate, Application $application): PositionCandidate;

    public function setScore(PositionCandidate $positionCandidate, array $score, int $totalScore): PositionCandidate;
}
