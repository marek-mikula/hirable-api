<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Illuminate\Database\Seeder;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

abstract class AbstractClassifierDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $type = $this->getType();

        $values = array_map(fn (string $value): array => [
            'type' => $type->value,
            'value' => $value,
        ], $this->getValues());

        Classifier::query()->insert($values);
    }

    abstract protected function getValues(): array;

    abstract protected function getType(): ClassifierTypeEnum;
}
