<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Repositories\PositionCandidateActionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PositionCandidateActionDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateActionRepositoryInterface $positionCandidateActionRepository,
    ) {
    }

    public function handle(PositionCandidateAction $positionCandidateAction): void
    {
        DB::transaction(function () use ($positionCandidateAction): void {
            $this->positionCandidateActionRepository->delete($positionCandidateAction);
        }, attempts: 5);
    }
}
