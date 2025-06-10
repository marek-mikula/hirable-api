<?php

declare(strict_types=1);

namespace Domain\Company\Policies;

use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\User\Models\User;

class CompanyPolicy
{
    public function show(User $user, Company $company): bool
    {
        return $user->company_id === $company->id;
    }

    public function update(User $user, Company $company): bool
    {
        return $this->show($user, $company) && $user->company_role === RoleEnum::ADMIN;
    }

    public function showUsers(User $user, Company $company): bool
    {
        return $this->show($user, $company) && $user->company_role === RoleEnum::ADMIN;
    }

    public function showInvitations(User $user, Company $company): bool
    {
        return $this->show($user, $company) && $user->company_role === RoleEnum::ADMIN;
    }

    public function storeInvitation(User $user, Company $company): bool
    {
        return $this->show($user, $company) && $user->company_role === RoleEnum::ADMIN;
    }

    public function updateInvitation(User $user, Company $company): bool
    {
        return $this->show($user, $company) && $user->company_role === RoleEnum::ADMIN;
    }

    public function showContacts(User $user, Company $company): bool
    {
        return $this->show($user, $company);
    }

    public function storeContact(User $user, Company $company): bool
    {
        return $this->show($user, $company);
    }

    public function updateContact(User $user, Company $company, CompanyContact $contact): bool
    {
        return $this->show($user, $company) && $company->id === $contact->company_id;
    }

    public function deleteContact(User $user, Company $company, CompanyContact $contact): bool
    {
        return $this->show($user, $company) && $company->id === $contact->company_id;
    }
}
