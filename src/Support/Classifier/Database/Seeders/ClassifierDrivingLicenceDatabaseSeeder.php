<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierDrivingLicenceDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'am',
            'a1',
            'a2',
            'a',
            'b',
            'b96',
            'be',
            'c1',
            'c1e',
            'c',
            'ce',
            'd1',
            'd1e',
            'd',
            'de',
            't',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::DRIVING_LICENCE;
    }
}
