<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionCandidatePriorityEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PositionCandidateSetPriorityUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateRepositoryInterface $positionCandidateRepository,
    ) {
    }

    public function handle(PositionCandidate $positionCandidate, PositionCandidatePriorityEnum $priority): PositionCandidate
    {
        return DB::transaction(function () use ($positionCandidate, $priority): PositionCandidate {
            return $this->positionCandidateRepository->setPriority($positionCandidate, $priority);
        }, attempts: 5);
    }
}
