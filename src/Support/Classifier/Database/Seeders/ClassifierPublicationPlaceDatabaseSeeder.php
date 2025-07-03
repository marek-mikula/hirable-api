<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierPublicationPlaceDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'facebook',
            'linkedin',
            'reddit',
            'teamio',
            'profesia',
            'atmoskop',
            'nelisa',
            'career_page',
            'startup_jobs',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::PUBLICATION_PLACE;
    }
}
