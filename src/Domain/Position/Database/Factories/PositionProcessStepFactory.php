<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Company\Models\Company;
use Domain\Position\Enums\PositionProcessStepEnum;
use Domain\Position\Models\PositionProcessStep;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<PositionProcessStep>
 */
class PositionProcessStepFactory extends Factory
{
    protected $model = PositionProcessStep::class;

    public function definition(): array
    {
        return [
            'company_id' => null,
            'step' => fake()->unique()->randomElement(PositionProcessStepEnum::cases()),
        ];
    }

    public function ofCompany(Company $company): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => $company->id,
        ]);
    }

    public function ofStep(PositionProcessStepEnum|string $step): static
    {
        return $this->state(fn (array $attributes) => [
            'step' => is_string($step) ? $step : $step->value,
        ]);
    }
}
