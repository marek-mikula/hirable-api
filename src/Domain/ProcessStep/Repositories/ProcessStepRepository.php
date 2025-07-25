<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories;

use App\Exceptions\RepositoryException;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\Inputs\ProcessStepStoreInput;

class ProcessStepRepository implements ProcessStepRepositoryInterface
{
    public function store(ProcessStepStoreInput $input): ProcessStep
    {
        $step = new ProcessStep();

        $step->company_id = $input->company->id;
        $step->step = $input->step;

        throw_if(!$step->save(), RepositoryException::stored(ProcessStep::class));

        $step->setRelation('company', $input->company);

        return $step;
    }
}
