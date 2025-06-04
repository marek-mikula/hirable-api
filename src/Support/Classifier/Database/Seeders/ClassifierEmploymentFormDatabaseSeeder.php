<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierEmploymentFormDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'on_site',
            'remote',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::EMPLOYMENT_FORM;
    }
}
