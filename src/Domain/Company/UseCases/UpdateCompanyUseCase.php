<?php

namespace Domain\Company\UseCases;

use App\Models\Company;
use App\Models\User;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Company\Input\UpdateInput;
use App\UseCases\UseCase;

class UpdateCompanyUseCase extends UseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function handle(User $user, array $values): Company
    {
        $company = $user->loadMissing('company')->company;

        if (empty($values)) {
            return $company;
        }

        $input = [
            'name' => $company->name,
            'email' => $company->email,
            'idNumber' => $company->id_number,
            'website' => $company->website,
        ];

        foreach ($values as $key => $value) {
            $input[$key] = $value;
        }

        return $this->companyRepository->update($company, UpdateInput::from($input));
    }
}
