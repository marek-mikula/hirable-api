<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierInterviewTypeDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'screening',
            'hr',
            'manager',
            'competency',
            'technical',
            'final',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::INTERVIEW_TYPE;
    }
}
