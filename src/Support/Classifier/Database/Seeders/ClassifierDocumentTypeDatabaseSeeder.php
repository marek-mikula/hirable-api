<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierDocumentTypeDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'cv',
            'cover_letter',
            'certificate',
            'educational_certificate',
            'reference',
            'criminal_record',
            'health_certificate',
            'other',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::DOCUMENT_TYPE;
    }
}
