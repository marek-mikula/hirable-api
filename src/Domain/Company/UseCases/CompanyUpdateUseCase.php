<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\Company;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyUpdateInput;
use Domain\User\Models\User;

class CompanyUpdateUseCase extends UseCase
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
            'environment' => $company->environment,
            'benefits' => $company->benefits,
        ];

        foreach ($values as $key => $value) {
            $input[$key] = $value;
        }

        return $this->companyRepository->update($company, new CompanyUpdateInput(...$input));
    }
}
