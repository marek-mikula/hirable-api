<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionProcessStepStoreInput;
use Domain\ProcessStep\Enums\StepEnum;
use Illuminate\Database\Eloquent\Collection;

class PositionProcessStepRepository implements PositionProcessStepRepositoryInterface
{
    public function store(PositionProcessStepStoreInput $input): PositionProcessStep
    {
        $positionProcessStep = new PositionProcessStep();

        $positionProcessStep->position_id = $input->position->id;
        $positionProcessStep->step = $input->step;
        $positionProcessStep->order = $input->order;
        $positionProcessStep->is_fixed = $input->isFixed;
        $positionProcessStep->is_repeatable = $input->isRepeatable;

        throw_if(!$positionProcessStep->save(), RepositoryException::stored(PositionProcessStep::class));

        $positionProcessStep->setRelation('position', $input->position);

        return $positionProcessStep;
    }

    public function findByPosition(Position $position, StepEnum|string $step): ?PositionProcessStep
    {
        /** @var PositionProcessStep|null $positionProcessStep */
        $positionProcessStep = PositionProcessStep::query()
            ->wherePosition($position->id)
            ->where('step', $step)
            ->first();

        return $positionProcessStep;
    }

    public function updateOrder(PositionProcessStep $positionProcessStep, int $order): PositionProcessStep
    {
        $positionProcessStep->order = $order;

        throw_if(!$positionProcessStep->save(), RepositoryException::updated(PositionProcessStep::class));

        return $positionProcessStep;
    }

    public function getStepsForKanban(Position $position): Collection
    {
        return PositionProcessStep::query()
            ->with([
                'positionCandidates',
                'positionCandidates.candidate',
            ])
            ->orderBy('order')
            ->get();
    }
}
