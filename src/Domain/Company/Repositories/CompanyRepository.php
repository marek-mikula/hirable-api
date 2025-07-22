<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Company\Models\Company;
use Domain\Company\Repositories\Input\CompanyStoreInput;
use Domain\Company\Repositories\Input\CompanyUpdateInput;

final class CompanyRepository implements CompanyRepositoryInterface
{
    public function find(int $id): ?Company
    {
        /** @var Company|null $company */
        $company = Company::query()
            ->find($id);

        return $company;
    }

    public function store(CompanyStoreInput $input): Company
    {
        $company = new Company();

        $company->language = $input->language;
        $company->name = $input->name;
        $company->email = $input->email;
        $company->id_number = $input->idNumber;
        $company->website = $input->website;

        throw_if(!$company->save(), RepositoryException::stored(Company::class));

        return $company;
    }

    public function update(Company $company, CompanyUpdateInput $input): Company
    {
        $company->language = $input->language;
        $company->name = $input->name;
        $company->email = $input->email;
        $company->id_number = $input->idNumber;
        $company->website = $input->website;

        throw_if(!$company->save(), RepositoryException::updated(Company::class));

        return $company;
    }
}
