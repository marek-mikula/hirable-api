<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PositionCandidateSetStepUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateRepositoryInterface $positionCandidateRepository,
    ) {
    }

    public function handle(
        Position $position,
        PositionCandidate $positionCandidate,
        PositionProcessStep $positionProcessStep
    ): PositionCandidate {
        return DB::transaction(fn (): PositionCandidate => $this->positionCandidateRepository->setStep($positionCandidate, $positionProcessStep), attempts: 5);
    }
}
