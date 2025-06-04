<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\Input\CompanyContactStoreInput;
use Illuminate\Database\Eloquent\Collection;

interface CompanyContactRepositoryInterface
{
    public function store(CompanyContactStoreInput $input): CompanyContact;

    /**
     * @param int[] $ids
     * @return Collection<CompanyContact>
     */
    public function getByIdsAndCompany(Company $company, array $ids): Collection;
}
