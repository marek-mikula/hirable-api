<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Models\Company;

interface CompanyBenefitRepositoryInterface
{
    /**
     * @param string[] $benefits
     */
    public function syncBenefits(Company $company, array $benefits): Company;
}
