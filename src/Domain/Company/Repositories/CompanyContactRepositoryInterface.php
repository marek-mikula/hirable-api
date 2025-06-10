<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\Input\CompanyContactStoreInput;
use Domain\Company\Repositories\Input\CompanyContactUpdateInput;
use Illuminate\Database\Eloquent\Collection;

interface CompanyContactRepositoryInterface
{
    public function store(CompanyContactStoreInput $input): CompanyContact;

    public function update(CompanyContact $contact, CompanyContactUpdateInput $input): CompanyContact;

    public function delete(CompanyContact $contact): void;

    /**
     * @param int[] $ids
     * @return Collection<CompanyContact>
     */
    public function getByIdsAndCompany(Company $company, array $ids): Collection;
}
