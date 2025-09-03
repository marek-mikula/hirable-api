<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PositionSetProcessStepOrderUseCase extends UseCase
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
    ) {
    }

    /**
     * @param string[] $order
     */
    public function handle(Position $position, array $order): void
    {
        $positionProcessSteps = $this->positionProcessStepRepository->index($position);

        $positionProcessSteps = $positionProcessSteps->sort(
            function (PositionProcessStep $a, PositionProcessStep $b) use ($order): int {
                $indexA = array_search(is_string($a->step) ? $a->step : $a->step->value, $order);
                $indexB = array_search(is_string($b->step) ? $b->step : $b->step->value, $order);

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

        dump($positionProcessSteps->all());

        DB::transaction(function () use ($positionProcessSteps): void {
            /**
             * @var int $index
             * @var PositionProcessStep $positionProcessStep
             */
            foreach ($positionProcessSteps as $index => $positionProcessStep) {
                if ($positionProcessStep->order !== $index) {
                    $this->positionProcessStepRepository->updateOrder($positionProcessStep, $index);
                }
            }
        }, attempts: 5);
    }
}
