<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Http\Requests\Data\ContactStoreData;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyContactStoreInput;

class CompanyContactStoreUseCase extends UseCase
{
    public function __construct(
        private readonly CompanyContactRepositoryInterface $companyContactRepository,
    ) {
    }

    public function handle(Company $company, ContactStoreData $data): CompanyContact
    {
        $input = new CompanyContactStoreInput(
            company: $company,
            language: $data->language,
            firstname: $data->firstname,
            lastname: $data->lastname,
            email: $data->email,
            note: $data->note,
            companyName: $data->companyName,
        );

        return $this->companyContactRepository->store($input);
    }
}
