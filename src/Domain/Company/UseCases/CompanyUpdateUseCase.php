<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\Company;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyUpdateInput;
use Illuminate\Support\Facades\DB;

class CompanyUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function handle(Company $company, array $values): Company
    {
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

        return DB::transaction(function () use ($company, $input): Company {
            return $this->companyRepository->update($company, new CompanyUpdateInput(...$input));
        }, attempts: 5);
    }
}
