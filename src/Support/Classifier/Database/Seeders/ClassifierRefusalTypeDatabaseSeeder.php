<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierRefusalTypeDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'accepted_other_offer',
            'no_longer_available',
            'lost_interest',
            'personal_change',
            'unsatisfied_offer',
            'location_issue',
            'schedule_issue',
            'process_too_long',
            'negative_interview_experience',
            'career_misalignment',
            'poor_communication',
            'company_instability',
            'staying_with_current_employer',
            'family_reasons',
            'other_reason',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::REFUSAL_TYPE;
    }
}
