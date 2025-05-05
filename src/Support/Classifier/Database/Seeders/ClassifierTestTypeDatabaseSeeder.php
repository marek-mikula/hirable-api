<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierTestTypeDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'logical_reasoning',
            'verbal_reasoning',
            'numerical_reasoning',
            'analytical_thinking',
            'personality_test',
            'psychological_test',
            'work_style_test',
            'problem_solving',
            'english_language_test',
            'foreign_language_test',
            'technical_knowledge_test',
            'nontechnical_knowledge_test',
            'computer_skills_test',
            'ms_office_test',
            'programming_test',
            'sales_skills_test',
            'real_situation_simulation',
            'assessment_center',
            'manual_skills_test',
            'attention_test',
            'memory_test',
            'time_management_test',
            'values_motivation_test',
            'stress_resistance_test',
            'other_test',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::TEST_TYPE;
    }
}
