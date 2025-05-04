<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Models\Company;
use Domain\Company\Repositories\Input\CompanyStoreInput;
use Domain\Company\Repositories\Input\CompanyUpdateInput;

interface CompanyRepositoryInterface
{
    public function find(int $id): ?Company;

    public function store(CompanyStoreInput $input): Company;

    public function update(Company $company, CompanyUpdateInput $input): Company;
}
