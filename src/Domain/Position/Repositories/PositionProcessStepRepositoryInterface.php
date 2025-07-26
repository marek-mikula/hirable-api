<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionProcessStepStoreInput;

interface PositionProcessStepRepositoryInterface
{
    public function store(PositionProcessStepStoreInput $input): PositionProcessStep;
}
