<?php

declare(strict_types=1);

namespace Domain\Company\Database\Factories;

use Database\Factories\Factory;
use Domain\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company,
            'email' => fake()->unique()->companyEmail,
            'id_number' => fake()->numerify('#########'),
            'website' => fake()->url,
            'culture' => null,
        ];
    }

    public function ofEmail(string $email): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => $email,
        ]);
    }

    public function ofIdNumber(string $idNumber): static
    {
        return $this->state(fn (array $attributes) => [
            'id_number' => $idNumber,
        ]);
    }
}
