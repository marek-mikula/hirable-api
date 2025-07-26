<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\ProcessStep\Enums\ProcessStepEnum;
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
            'position_id' => $this->isMaking ? null : Position::factory(),
            'step' => ProcessStepEnum::NEW,
            'round' => null,
        ];
    }

    public function ofPosition(Position $position): static
    {
        return $this->state(fn (array $attributes) => [
            'position_id' => $position->id,
        ]);
    }

    public function ofStep(ProcessStepEnum|string $step): static
    {
        return $this->state(fn (array $attributes) => [
            'step' => is_string($step) ? $step : $step->value,
        ]);
    }
}
