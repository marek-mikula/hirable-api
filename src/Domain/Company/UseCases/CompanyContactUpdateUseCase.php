<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Http\Requests\Data\ContactData;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyContactUpdateInput;
use Illuminate\Support\Facades\DB;

class CompanyContactUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly CompanyContactRepositoryInterface $companyContactRepository,
    ) {
    }

    public function handle(CompanyContact $contact, ContactData $data): CompanyContact
    {
        $input = new CompanyContactUpdateInput(
            language: $data->language,
            firstname: $data->firstname,
            lastname: $data->lastname,
            email: $data->email,
            note: $data->note,
            companyName: $data->companyName,
        );

        return DB::transaction(function () use ($contact, $input): CompanyContact {
            return $this->companyContactRepository->update($contact, $input);
        }, attempts: 5);
    }
}
