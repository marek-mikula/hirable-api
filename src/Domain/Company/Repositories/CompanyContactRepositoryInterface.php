<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\Input\CompanyContactStoreInput;

interface CompanyContactRepositoryInterface
{
    public function store(CompanyContactStoreInput $input): CompanyContact;
}
