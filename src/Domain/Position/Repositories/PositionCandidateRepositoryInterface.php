<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionCandidateStoreInput;

interface PositionCandidateRepositoryInterface
{
    public function store(PositionCandidateStoreInput $input): PositionCandidate;

    public function setScore(PositionCandidate $positionCandidate, array $score, int $totalScore): PositionCandidate;

    public function setStep(PositionCandidate $positionCandidate, PositionProcessStep $positionProcessStep): PositionCandidate;
}
