<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierFieldDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'admin',
            'auto_moto',
            'banking_finance',
            'tourism_hospitality',
            'chemical_industry',
            'logistics_supply',
            'corporate_finance',
            'electrical_energy',
            'pharmacy',
            'hospitality',
            'it_consulting',
            'it_admin',
            'it_development',
            'arts_creative',
            'quality_control',
            'marketing',
            'media_advertising_pr',
            'procurement',
            'security',
            'hr',
            'insurance',
            'food_industry',
            'sales',
            'legal',
            'services',
            'construction_realestate',
            'engineering',
            'public_admin',
            'technical_development',
            'telecom',
            'executive_management',
            'publishing_print',
            'education',
            'manufacturing_industry',
            'science_research',
            'healthcare_social',
            'agriculture_ecology',
            'customer_service',
            'manual_labor',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::FIELD;
    }
}
