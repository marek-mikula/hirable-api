<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionProcessStepStoreInput;

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
}
