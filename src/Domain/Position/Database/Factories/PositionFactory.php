<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Company\Models\Company;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $salarySpan = fake()->boolean;
        $isTechnical = fake()->boolean;

        $currencies = [
            'CZK',
            'USD',
            'EUR',
        ];

        $salaryFrequencies = [
            'hourly',
            'daily',
            'monthly',
            'quarterly',
            'yearly',
        ];

        $salary = fake()->numberBetween(5000, 150000);

        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'name' => fake()->jobTitle,
            'department' => str(fake()->word)->transliterate()->lower()->snake()->toString(),
            'field' => str(fake()->word)->transliterate()->lower()->snake()->toString(),
            'is_technical' => $isTechnical,
            'address' => fake()->address,
            'salary_from' => $salary,
            'salary_to' => $salarySpan ? ($salary + fake()->numberBetween(10000, 50000)) : null,
            'salary_frequency' => fake()->randomElement($salaryFrequencies),
            'salary_currency' => fake()->randomElement($currencies),
            'salary_var' => fake()->words(asText: true),
            'min_education_level' => str(fake()->word)->transliterate()->lower()->snake()->toString(),
            'seniority' => $isTechnical ? str(fake()->word)->transliterate()->lower()->snake()->toString() : null,
            'experience' => fake()->numberBetween(0, 10),
            'note' => fake()->boolean ? fake()->text(500) : null,
        ];
    }
}
