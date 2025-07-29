<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionProcessStepStoreInput;
use Domain\ProcessStep\Enums\StepEnum;
use Illuminate\Database\Eloquent\Collection;

interface PositionProcessStepRepositoryInterface
{
    public function store(PositionProcessStepStoreInput $input): PositionProcessStep;

    public function findByPosition(Position $position, StepEnum|string $step): ?PositionProcessStep;

    public function getMaxOrder(Position $position): int;

    public function hasStep(Position $position, StepEnum|string $step): bool;

    public function updateOrder(PositionProcessStep $positionProcessStep, int $order): PositionProcessStep;

    /**
     * @return Collection<PositionProcessStep>
     */
    public function getStepsForKanban(Position $position): Collection;
}
