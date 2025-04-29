<?php

declare(strict_types=1);

namespace App\Repositories\Company;

use App\Models\Company;
use App\Repositories\Company\Input\StoreInput;
use App\Repositories\Company\Input\UpdateInput;

interface CompanyRepositoryInterface
{
    public function find(int $id): ?Company;

    public function store(StoreInput $input): Company;

    public function update(Company $company, UpdateInput $input): Company;
}
