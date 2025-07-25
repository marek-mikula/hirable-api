<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories;

use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\Inputs\ProcessStepStoreInput;

interface ProcessStepRepositoryInterface
{
    public function store(ProcessStepStoreInput $input): ProcessStep;
}
