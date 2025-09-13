<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\ProcessStep\Enums\StepEnum;

/**
 * @extends Factory<PositionProcessStep>
 */
class PositionProcessStepFactory extends Factory
{
    protected $model = PositionProcessStep::class;

    public function definition(): array
    {
        return [
            'position_id' => $this->isMaking ? null : Position::factory(),
            'step' => fake()->word,
            'label' => null,
            'order' => fake()->numberBetween(0, 20),
            'is_fixed' => false,
            'is_repeatable' => fake()->boolean,
            'triggers_action' => null,
        ];
    }

    public function ofPosition(Position $position): static
    {
        return $this->state(fn (array $attributes): array => [
            'position_id' => $position->id,
        ]);
    }

    public function ofStep(StepEnum|string $step): static
    {
        return $this->state(fn (array $attributes): array => [
            'step' => is_string($step) ? $step : $step->value,
        ]);
    }
}
