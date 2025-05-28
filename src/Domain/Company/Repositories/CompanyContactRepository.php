<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\Input\CompanyContactStoreInput;

class CompanyContactRepository implements CompanyContactRepositoryInterface
{
    public function store(CompanyContactStoreInput $input): CompanyContact
    {
        $contact = new CompanyContact();

        $contact->company_id = $input->company->id;
        $contact->firstname = $input->firstname;
        $contact->lastname = $input->lastname;
        $contact->email = $input->email;
        $contact->note = $input->note;
        $contact->company_name = $input->companyName;

        throw_if(!$contact->save(), RepositoryException::stored(CompanyContact::class));

        $contact->setRelation('company', $input->company);

        return $contact;
    }
}
