<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Factories;

use Database\Factories\Factory;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

/**
 * @extends Factory<Classifier>
 */
class ClassifierFactory extends Factory
{
    protected $model = Classifier::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(ClassifierTypeEnum::cases()),
            'value' => str(fake()->unique()->word)->lower()->snake()->toString(),
        ];
    }

    public function ofType(ClassifierTypeEnum $type): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => $type,
        ]);
    }

    public function ofValue(string $value): static
    {
        return $this->state(fn (array $attributes): array => [
            'value' => $value,
        ]);
    }
}
