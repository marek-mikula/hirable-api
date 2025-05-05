<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyBenefit;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

final class CompanyBenefitRepository implements CompanyBenefitRepositoryInterface
{
    public function syncBenefits(Company $company, array $benefits): Company
    {
        // remove all benefits first
        CompanyBenefit::query()->whereCompany($company->id)->delete();

        if (!empty($benefits)) {
            $insert = Classifier::query()
                ->whereType(ClassifierTypeEnum::BENEFIT)
                ->whereValue($benefits)
                ->get()
                ->map(fn (Classifier $item) => [
                    'company_id' => $company->id,
                    'benefit_id' => $item->id,
                ])
                ->all();
        } else {
            $insert = [];
        }

        if (!empty($insert)) {
            CompanyBenefit::query()->insert($insert);
        }

        if ($company->relationLoaded('benefits')) {
            $company->load('benefits');
        }

        if ($company->relationLoaded('companyBenefits')) {
            $company->load('companyBenefits');
        }

        return $company;
    }
}
