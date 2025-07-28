<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Company\Models\Company;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\Inputs\ProcessStepStoreInput;
use Illuminate\Database\Eloquent\Collection;

class ProcessStepRepository implements ProcessStepRepositoryInterface
{
    public function store(ProcessStepStoreInput $input): ProcessStep
    {
        $processStep = new ProcessStep();

        $processStep->company_id = $input->company->id;
        $processStep->step = $input->step;
        $processStep->is_fixed = false;
        $processStep->is_repeatable = $input->isRepeatable;

        throw_if(!$processStep->save(), RepositoryException::stored(ProcessStep::class));

        $processStep->setRelation('company', $input->company);

        return $processStep;
    }

    public function delete(ProcessStep $processStep): void
    {
        throw_if(!$processStep->delete(), RepositoryException::deleted(ProcessStep::class));
    }

    public function getByCompany(Company $company): Collection
    {
        return ProcessStep::query()
            ->where('company_id', $company->id)
            ->get();
    }
}
