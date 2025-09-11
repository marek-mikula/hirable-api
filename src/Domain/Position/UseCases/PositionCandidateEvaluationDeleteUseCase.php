<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\PositionCandidateEvaluationRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionCandidateEvaluationDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateEvaluationRepositoryInterface $positionCandidateEvaluationRepository,
    ) {
    }

    public function handle(User $user, Position $position, PositionCandidate $positionCandidate, PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        DB::transaction(function () use ($positionCandidateEvaluation): void {
            $this->positionCandidateEvaluationRepository->delete($positionCandidateEvaluation);
        }, attempts: 5);
    }
}
