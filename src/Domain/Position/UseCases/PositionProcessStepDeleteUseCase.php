<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PositionProcessStepDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
    ) {
    }

    public function handle(Position $position, PositionProcessStep $positionProcessStep): void
    {
        abort_if($positionProcessStep->is_fixed, code: 400);

        abort_if($this->positionProcessStepRepository->stepHasCandidates($positionProcessStep), code: 400);

        DB::transaction(function () use ($positionProcessStep): void {
            $this->positionProcessStepRepository->delete($positionProcessStep);
        }, attempts: 5);
    }
}
