<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Illuminate\Database\Seeder;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Services\ClassifierConfigService;

class ClassifierDatabaseSeeder extends Seeder
{
    public function __construct(
        private readonly ClassifierConfigService $classifierConfigService
    ) {
    }

    public function run(): void
    {
        foreach (ClassifierTypeEnum::cases() as $type) {
            $this->call($this->classifierConfigService->getSeeder($type));
        }
    }
}
