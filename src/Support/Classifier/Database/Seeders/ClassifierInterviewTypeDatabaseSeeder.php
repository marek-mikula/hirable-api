<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierInterviewTypeDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'phone_interview',
            'live_video_interview',
            'pre_recorded_video_interview',
            'in_person_interview',
            'online_interview',
            'group_interview',
            'panel_interview',
            'hr_interview',
            'manager_interview',
            'executive_interview',
            'technical_interview',
            'foreign_language_interview',
            'client_interview',
            'structured_interview',
            'unstructured_interview',
            'behavioral_interview',
            'competency_interview',
            'stress_interview',
            'case_interview',
            'assessment_center',
            'follow_up_interview',
            'final_interview',
            'other_interview',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::INTERVIEW_TYPE;
    }
}
