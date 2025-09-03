<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierInterviewFormDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'personal',
            'phone',
            'online',
            'async',
            'chat',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::INTERVIEW_FORM;
    }
}
