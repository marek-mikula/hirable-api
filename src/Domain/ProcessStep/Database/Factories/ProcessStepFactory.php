<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Database\Factories;

use Database\Factories\Factory;
use Domain\Company\Models\Company;
use Domain\ProcessStep\Enums\StepEnum;
use Domain\ProcessStep\Models\ProcessStep;

/**
 * @extends Factory<ProcessStep>
 */
class ProcessStepFactory extends Factory
{
    protected $model = ProcessStep::class;

    public function definition(): array
    {
        return [
            'company_id' => null,
            'step' => fake()->word,
            'is_repeatable' => fake()->boolean,
            'triggers_action' => null,
        ];
    }

    public function ofCompany(Company $company): static
    {
        return $this->state(fn (array $attributes): array => [
            'company_id' => $company->id,
        ]);
    }

    public function ofStep(StepEnum|string $step): static
    {
        return $this->state(fn (array $attributes): array => [
            'step' => is_string($step) ? $step : $step->value,
        ]);
    }
}
