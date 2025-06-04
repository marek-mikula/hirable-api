<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Models\Builders\CompanyContactBuilder;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;

class CompanyContactSuggestRepository implements CompanyContactSuggestRepositoryInterface
{
    public function suggestCompanies(Company $company, ?string $value): array
    {
        return CompanyContact::query()
            ->select('company_name')
            ->whereNotNull('company_name')
            ->where('company_id', $company->id)
            ->when(!empty($value), function (CompanyContactBuilder $query) use ($value): void {
                $query->where('company_name', 'like', "%{$value}%");
            })
            ->orderBy('company_name')
            ->distinct()
            ->pluck('company_name')
            ->all();
    }
}
