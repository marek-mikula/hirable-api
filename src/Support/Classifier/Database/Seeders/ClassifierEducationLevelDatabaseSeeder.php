<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierEducationLevelDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'primary',
            'secondary_no_certificate',
            'secondary_certificate',
            'secondary_practice_certificate',
            'higher',
            'bachelor',
            'master',
            'doctor'
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::EDUCATION_LEVEL;
    }
}
