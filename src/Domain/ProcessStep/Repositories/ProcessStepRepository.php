<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Company\Models\Company;
use Domain\ProcessStep\Models\Builders\ProcessStepBuilder;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\Inputs\ProcessStepStoreInput;
use Domain\ProcessStep\Repositories\Inputs\ProcessStepUpdateInput;
use Illuminate\Database\Eloquent\Collection;

class ProcessStepRepository implements ProcessStepRepositoryInterface
{
    public function store(ProcessStepStoreInput $input): ProcessStep
    {
        $processStep = new ProcessStep();

        $processStep->company_id = $input->company->id;
        $processStep->step = $input->step;
        $processStep->is_repeatable = $input->isRepeatable;
        $processStep->triggers_action = $input->triggersAction;

        throw_if(!$processStep->save(), RepositoryException::stored(ProcessStep::class));

        $processStep->setRelation('company', $input->company);

        return $processStep;
    }

    public function find(int $id): ?ProcessStep
    {
        /** @var ProcessStep|null $processStep */
        $processStep = ProcessStep::query()->find($id);

        return $processStep;
    }

    public function update(ProcessStep $processStep, ProcessStepUpdateInput $input): ProcessStep
    {
        $processStep->step = $input->step;
        $processStep->is_repeatable = $input->isRepeatable;
        $processStep->triggers_action = $input->triggersAction;

        throw_if(!$processStep->save(), RepositoryException::updated(ProcessStep::class));

        return $processStep;
    }

    public function delete(ProcessStep $processStep): void
    {
        throw_if(!$processStep->delete(), RepositoryException::deleted(ProcessStep::class));
    }

    public function getByCompany(Company $company, bool $includeCommon = false): Collection
    {
        return ProcessStep::query()
            ->where(function (ProcessStepBuilder $query) use ($company, $includeCommon): void {
                $query->whereCompany($company->id);

                if ($includeCommon) {
                    $query->orWhereNull('company_id');
                }
            })
            ->get();
    }
}
