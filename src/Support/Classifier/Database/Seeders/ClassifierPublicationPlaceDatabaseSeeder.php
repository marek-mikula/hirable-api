<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierPublicationPlaceDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'teamio',
            'startup_jobs',
            'linkedin',
            'atmoskop',
            'career_page',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::PUBLICATION_PLACE;
    }
}
