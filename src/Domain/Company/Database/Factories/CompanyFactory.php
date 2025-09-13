<?php

declare(strict_types=1);

namespace Domain\Company\Database\Factories;

use App\Enums\LanguageEnum;
use Database\Factories\Factory;
use Domain\Company\Models\Company;

/**
 * @extends Factory<Company>
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
            'ai_output_language' => fake()->randomElement(LanguageEnum::cases()),
        ];
    }

    public function ofEmail(string $email): static
    {
        return $this->state(fn (array $attributes): array => [
            'email' => $email,
        ]);
    }

    public function ofIdNumber(string $idNumber): static
    {
        return $this->state(fn (array $attributes): array => [
            'id_number' => $idNumber,
        ]);
    }
}
