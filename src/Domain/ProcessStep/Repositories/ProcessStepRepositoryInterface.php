<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories;

use Domain\Company\Models\Company;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\Inputs\ProcessStepStoreInput;
use Domain\ProcessStep\Repositories\Inputs\ProcessStepUpdateInput;
use Illuminate\Database\Eloquent\Collection;

interface ProcessStepRepositoryInterface
{
    public function store(ProcessStepStoreInput $input): ProcessStep;

    public function find(int $id): ?ProcessStep;

    public function update(ProcessStep $processStep, ProcessStepUpdateInput $input): ProcessStep;

    public function delete(ProcessStep $processStep): void;

    /**
     * @return Collection<ProcessStep>
     */
    public function getByCompany(Company $company, bool $includeCommon): Collection;
}
