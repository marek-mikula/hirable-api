<?php

declare(strict_types=1);

use Support\Classifier\Database\Seeders\ClassifierBenefitDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierCurrencyDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierEducationLevelDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierEmploymentFormDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierEmploymentRelationshipDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierGenderDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierFieldDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierInterviewTypeDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierLanguageDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierLanguageLevelDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierPhonePrefixDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierRefusalTypeDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierRejectionTypeDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierSalaryFrequencyDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierSalaryTypeDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierSeniorityDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierTestTypeDatabaseSeeder;
use Support\Classifier\Database\Seeders\ClassifierWorkloadDatabaseSeeder;
use Support\Classifier\Enums\ClassifierTypeEnum;

return [

    'cache_enabled' => env('CLASSIFIER_CACHE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Classifier cache time (in minutes)
    |--------------------------------------------------------------------------
    |
    | This parameter controls the cache time of the classifier
    | values.
    |
    */

    'cache_time' => env('CLASSIFIER_CACHE_TIME', 7 * 24 * 60),

    'types' => [
        ClassifierTypeEnum::GENDER->value => [
            'translate' => true,
            'seeder' => ClassifierGenderDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::CURRENCY->value => [
            'translate' => false,
            'seeder' => ClassifierCurrencyDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::LANGUAGE->value => [
            'translate' => true,
            'seeder' => ClassifierLanguageDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::LANGUAGE_LEVEL->value => [
            'translate' => true,
            'seeder' => ClassifierLanguageLevelDatabaseSeeder::class,
            'order' => [
                'a1',
                'a2',
                'b1',
                'b2',
                'c1',
                'c2',
                'native',
            ]
        ],
        ClassifierTypeEnum::BENEFIT->value => [
            'translate' => true,
            'seeder' => ClassifierBenefitDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::WORKLOAD->value => [
            'translate' => true,
            'seeder' => ClassifierWorkloadDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP->value => [
            'translate' => true,
            'seeder' => ClassifierEmploymentRelationshipDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::EMPLOYMENT_FORM->value => [
            'translate' => true,
            'seeder' => ClassifierEmploymentFormDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::SENIORITY->value => [
            'translate' => true,
            'seeder' => ClassifierSeniorityDatabaseSeeder::class,
            'order' => [
                'junior',
                'medior',
                'senior',
            ]
        ],
        ClassifierTypeEnum::EDUCATION_LEVEL->value => [
            'translate' => true,
            'seeder' => ClassifierEducationLevelDatabaseSeeder::class,
            'order' => [
                'primary',
                'secondary_no_certificate',
                'secondary_certificate',
                'secondary_practice_certificate',
                'higher',
                'bachelor',
                'master',
                'doctor'
            ],
        ],
        ClassifierTypeEnum::FIELD->value => [
            'translate' => true,
            'seeder' => ClassifierFieldDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::PHONE_PREFIX->value => [
            'translate' => false,
            'seeder' => ClassifierPhonePrefixDatabaseSeeder::class,
            'order' => [
                '+420',
                '+421',
                '+49',
                '+48',
                '+43',
                '+380',
            ],
        ],
        ClassifierTypeEnum::INTERVIEW_TYPE->value => [
            'translate' => true,
            'seeder' => ClassifierInterviewTypeDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::TEST_TYPE->value => [
            'translate' => true,
            'seeder' => ClassifierTestTypeDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::REFUSAL_TYPE->value => [
            'translate' => true,
            'seeder' => ClassifierRefusalTypeDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::REJECTION_TYPE->value => [
            'translate' => true,
            'seeder' => ClassifierRejectionTypeDatabaseSeeder::class,
        ],
        ClassifierTypeEnum::SALARY_FREQUENCY->value => [
            'translate' => true,
            'seeder' => ClassifierSalaryFrequencyDatabaseSeeder::class,
            'order' => [
                'hourly',
                'daily',
                'monthly',
                'quarterly',
                'yearly',
            ],
        ],
        ClassifierTypeEnum::SALARY_TYPE->value => [
            'translate' => true,
            'seeder' => ClassifierSalaryTypeDatabaseSeeder::class,
        ],
    ],

];
