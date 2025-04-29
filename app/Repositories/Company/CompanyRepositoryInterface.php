<?php

declare(strict_types=1);

namespace App\Repositories\Company;

use App\Models\Company;
use App\Repositories\Company\Input\CompanyStoreInput;
use App\Repositories\Company\Input\CompanyUpdateInput;

interface CompanyRepositoryInterface
{
    public function find(int $id): ?Company;

    public function store(CompanyStoreInput $input): Company;

    public function update(Company $company, CompanyUpdateInput $input): Company;
}
