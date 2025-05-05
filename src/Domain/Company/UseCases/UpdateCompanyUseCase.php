<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\Company;
use Domain\Company\Repositories\CompanyBenefitRepositoryInterface;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyUpdateInput;
use Domain\User\Models\User;

class UpdateCompanyUseCase extends UseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly CompanyBenefitRepositoryInterface $companyBenefitRepository,
    ) {
    }

    public function handle(User $user, array $values): Company
    {
        $company = $user->loadMissing('company')->company;

        if (empty($values)) {
            return $company;
        }

        // benefits update
        if (array_key_exists('benefits', $values)) {
            $company = $this->companyBenefitRepository->syncBenefits($company, $values['benefits']);

            unset($values['benefits']);
        }

        $input = [
            'name' => $company->name,
            'email' => $company->email,
            'idNumber' => $company->id_number,
            'website' => $company->website,
            'culture' => $company->culture,
        ];

        foreach ($values as $key => $value) {
            $input[$key] = $value;
        }

        return $this->companyRepository->update($company, new CompanyUpdateInput(...$input));
    }
}
