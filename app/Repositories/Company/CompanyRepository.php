<?php

namespace App\Repositories\Company;

use App\Exceptions\RepositoryException;
use App\Models\Company;
use App\Repositories\Company\Input\StoreInput;
use App\Repositories\Company\Input\UpdateInput;

final class CompanyRepository implements CompanyRepositoryInterface
{
    public function find(int $id): ?Company
    {
        /** @var Company|null $company */
        $company = Company::query()
            ->find($id);

        return $company;
    }

    public function store(StoreInput $input): Company
    {
        $company = new Company();

        $company->name = $input->name;
        $company->email = $input->email;
        $company->id_number = $input->idNumber;
        $company->website = $input->website;

        throw_if(! $company->save(), RepositoryException::stored(Company::class));

        return $company;
    }

    public function update(Company $company, UpdateInput $input): Company
    {
        $company->name = $input->name;
        $company->email = $input->email;
        $company->id_number = $input->idNumber;
        $company->website = $input->website;

        throw_if(! $company->save(), RepositoryException::updated(Company::class));

        return $company;
    }
}
