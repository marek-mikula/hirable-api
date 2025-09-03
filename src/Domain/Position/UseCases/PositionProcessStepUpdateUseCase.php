<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Http\Request\Data\PositionProcessStepData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Input\PositionProcessStepUpdateInput;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PositionProcessStepUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
    ) {
    }

    public function handle(Position $position, PositionProcessStep $positionProcessStep, PositionProcessStepData $data): PositionProcessStep
    {
        $input = new PositionProcessStepUpdateInput(
            label: $data->label,
        );

        return DB::transaction(function () use ($positionProcessStep, $input): PositionProcessStep {
            return $this->positionProcessStepRepository->update($positionProcessStep, $input);
        }, attempts: 5);
    }
}
