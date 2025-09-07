<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Input\PositionCandidateStoreInput;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface PositionCandidateRepositoryInterface
{
    /**
     * @param string[] $with
     * @return Collection<PositionCandidate>
     */
    public function index(User $user, Position $position, array $with = []): Collection;

    public function store(PositionCandidateStoreInput $input): PositionCandidate;

    public function setScore(PositionCandidate $positionCandidate, array $score, int $totalScore): PositionCandidate;

    public function setStep(PositionCandidate $positionCandidate, PositionProcessStep $positionProcessStep): PositionCandidate;
}
