<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierInterviewTypeDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'screening_interview',
            'hr_interview',
            'manager_interview',
            'competency_interview',
            'technical_interview',
            'final_interview',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::INTERVIEW_TYPE;
    }
}
