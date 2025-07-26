<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Http\Request\Data\KanbanSettingsData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PositionUpdateKanbanSettingsUseCase extends UseCase
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
    ) {
    }

    /**
     * @return Collection<PositionProcessStep>
     */
    public function handle(Position $position, KanbanSettingsData $data): Collection
    {
        $positionProcessSteps = $this->positionProcessStepRepository->getStepsForKanban($position);

        $positionProcessSteps = $positionProcessSteps->sort(
            function (PositionProcessStep $a, PositionProcessStep $b) use ($data): int {
                $indexA = array_search(is_string($a->step) ? $a->step : $a->step->value, $data->order);
                $indexB = array_search(is_string($b->step) ? $b->step : $b->step->value, $data->order);

                // both values have priority order
                if ($indexA !== false && $indexB !== false) {
                    return $indexA - $indexB;
                }

                // only A has priority – it goes up
                if ($indexA !== false) {
                    return -1;
                }

                // only B has priority – it goes up
                if ($indexB !== false) {
                    return 1;
                }

                return 0;
            }
        )->values();

        return DB::transaction(function () use ($positionProcessSteps): Collection {
            /**
             * @var int $index
             * @var PositionProcessStep $positionProcessStep
             */
            foreach ($positionProcessSteps as $index => $positionProcessStep) {
                if ($positionProcessStep->order !== $index) {
                    $this->positionProcessStepRepository->updateOrder($positionProcessStep, $index);
                }
            }

            return $positionProcessSteps;
        }, attempts: 5);
    }
}
