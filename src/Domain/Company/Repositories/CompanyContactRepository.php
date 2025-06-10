<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\Input\CompanyContactStoreInput;
use Domain\Company\Repositories\Input\CompanyContactUpdateInput;
use Illuminate\Database\Eloquent\Collection;

class CompanyContactRepository implements CompanyContactRepositoryInterface
{
    public function store(CompanyContactStoreInput $input): CompanyContact
    {
        $contact = new CompanyContact();

        $contact->company_id = $input->company->id;
        $contact->language = $input->language;
        $contact->firstname = $input->firstname;
        $contact->lastname = $input->lastname;
        $contact->email = $input->email;
        $contact->note = $input->note;
        $contact->company_name = $input->companyName;

        throw_if(!$contact->save(), RepositoryException::stored(CompanyContact::class));

        $contact->setRelation('company', $input->company);

        return $contact;
    }

    public function update(CompanyContact $contact, CompanyContactUpdateInput $input): CompanyContact
    {
        $contact->language = $input->language;
        $contact->firstname = $input->firstname;
        $contact->lastname = $input->lastname;
        $contact->email = $input->email;
        $contact->note = $input->note;
        $contact->company_name = $input->companyName;

        throw_if(!$contact->save(), RepositoryException::updated(CompanyContact::class));

        return $contact;
    }

    public function getByIdsAndCompany(Company $company, array $ids): Collection
    {
        return CompanyContact::query()
            ->whereCompany($company->id)
            ->whereIn('id', $ids)
            ->get();
    }
}
