<?php

declare(strict_types=1);

namespace Domain\Company\Database\Factories;

use Database\Factories\Factory;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyBenefit;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

/**
 * @extends BaseFactory<CompanyBenefit>
 */
class CompanyBenefitFactory extends Factory
{
    protected $model = CompanyBenefit::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'benefit_id' => Classifier::factory()->ofType(ClassifierTypeEnum::BENEFIT),
        ];
    }

    public function ofCompany(Company $company): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => $company->id,
        ]);
    }

    public function ofBenefit(Classifier $benefit): static
    {
        return $this->state(fn (array $attributes) => [
            'benefit_id' => $benefit->id,
        ]);
    }
}
