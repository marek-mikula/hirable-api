<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Factories;

use Database\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

/**
 * @extends BaseFactory<Classifier>
 */
class ClassifierFactory extends Factory
{
    protected $model = Classifier::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(ClassifierTypeEnum::cases()),
            'value' => str(fake()->word)->lower()->snake(),
        ];
    }

    public function ofType(ClassifierTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    public function ofValue(string $value): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => $value,
        ]);
    }
}
