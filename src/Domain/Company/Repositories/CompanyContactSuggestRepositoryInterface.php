<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Models\Company;

interface CompanyContactSuggestRepositoryInterface
{
    /**
     * @return string[]
     */
    public function suggestCompanies(Company $company, ?string $value): array;
}
