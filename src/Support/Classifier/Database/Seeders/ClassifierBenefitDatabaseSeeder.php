<?php

declare(strict_types=1);

namespace Support\Classifier\Database\Seeders;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierBenefitDatabaseSeeder extends AbstractClassifierDatabaseSeeder
{
    protected function getValues(): array
    {
        return [
            'meal_contribution',
            'transport_contribution',
            'pension_contribution',
            'life_insurance_contribution',
            'more_vacation',
            'sick_days',
            'home_office',
            'flexible_hours',
            'company_car',
            'company_phone_laptop',
            'bonuses_commissions',
            'annual_bonus',
            'sports_card',
            'company_events',
            'free_snacks',
            'training_education',
            'career_growth',
            'childcare_support',
            'work_from_abroad',
            'culture_leisure_support',
            'employee_discounts',
            'housing_relocation_support',
            'equity_program',
            'unpaid_leave_option',
            'mental_health_support',
            'relax_zones',
            'company_parking',
            'free_coffee_tea',
            'seniority_vacation_bonus',
        ];
    }

    protected function getType(): ClassifierTypeEnum
    {
        return ClassifierTypeEnum::BENEFIT;
    }
}
