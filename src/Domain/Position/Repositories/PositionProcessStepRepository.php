<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionProcessStepStoreInput;
use Domain\ProcessStep\Enums\ProcessStepEnum;

class PositionProcessStepRepository implements PositionProcessStepRepositoryInterface
{
    public function store(PositionProcessStepStoreInput $input): PositionProcessStep
    {
        $positionProcessStep = new PositionProcessStep();

        $positionProcessStep->position_id = $input->position->id;
        $positionProcessStep->order = $input->order;
        $positionProcessStep->step = $input->step;
        $positionProcessStep->round = $input->round;

        throw_if(!$positionProcessStep->save(), RepositoryException::stored(PositionProcessStep::class));

        $positionProcessStep->setRelation('position', $input->position);

        return $positionProcessStep;
    }

    public function findByPosition(Position $position, ProcessStepEnum|string $step): ?PositionProcessStep
    {
        /** @var PositionProcessStep|null $positionProcessStep */
        $positionProcessStep = PositionProcessStep::query()
            ->wherePosition($position->id)
            ->where('step', $step)
            ->first();

        return $positionProcessStep;
    }
}
