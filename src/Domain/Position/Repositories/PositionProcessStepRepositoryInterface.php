<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Input\PositionProcessStepStoreInput;
use Domain\Position\Repositories\Input\PositionProcessStepUpdateInput;
use Domain\ProcessStep\Enums\StepEnum;
use Illuminate\Database\Eloquent\Collection;

interface PositionProcessStepRepositoryInterface
{
    public function store(PositionProcessStepStoreInput $input): PositionProcessStep;

    public function find(int $id, array $with): PositionProcessStep;

    public function delete(PositionProcessStep $positionProcessStep): void;

    public function update(PositionProcessStep $positionProcessStep, PositionProcessStepUpdateInput $input): PositionProcessStep;

    public function findByPosition(Position $position, StepEnum|string $step): ?PositionProcessStep;

    public function getMaxOrder(Position $position): int;

    public function positionHasStep(Position $position, StepEnum|string $step): bool;

    public function stepHasCandidates(PositionProcessStep $positionProcessStep): bool;

    public function updateOrder(PositionProcessStep $positionProcessStep, int $order): PositionProcessStep;

    /**
     * @param string[] $with
     * @return Collection<PositionProcessStep>
     */
    public function getByPosition(Position $position, array $with): Collection;
}
