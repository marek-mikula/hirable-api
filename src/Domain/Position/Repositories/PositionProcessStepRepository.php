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
        $step = new PositionProcessStep();

        $step->company_id = $input->company->id;
        $step->step = $input->step;

        throw_if(!$step->save(), RepositoryException::stored(PositionProcessStep::class));

        $step->setRelation('company', $input->company);

        return $step;
    }
}
