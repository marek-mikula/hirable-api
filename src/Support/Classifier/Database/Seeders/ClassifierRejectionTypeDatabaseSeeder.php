<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierRejectionTypeDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'lack_experience',
            'insufficient_education',
            'skill_mismatch',
            'salary_expectations',
            'location_mismatch',
            'availability_issue',
            'schedule_conflict',
            'lack_motivation',
            'failed_interview',
            'failed_testing',
            'cultural_mismatch',
            'candidate_declined',
            'position_cancelled',
            'position_filled',
            'overqualified',
            'incomplete_application',
            'lacking_language_skills',
            'failed_reference_check',
            'failed_background_check',
            'other_reason',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::REJECTION_TYPE;
    }
}
