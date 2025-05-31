<?php

declare(strict_types=1);

namespace Domain\Company\Database\Factories;

use App\Enums\LanguageEnum;
use Database\Factories\Factory;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<CompanyContact>
 */
class CompanyContactFactory extends Factory
{
    protected $model = CompanyContact::class;

    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);

        return [
            'company_id' => $this->isMaking ? null : Company::factory(),
            'language' => LanguageEnum::EN,
            'firstname' => fake()->firstName($gender),
            'lastname' => fake()->lastName($gender),
            'email' => fake()->unique()->email,
            'note' => fake()->boolean ? fake()->text(300) : null,
            'company_name' => fake()->company
        ];
    }

    public function ofEmail(string $email): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => $email,
        ]);
    }

    public function ofCompany(Company $company): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => $company->id,
        ]);
    }
}
