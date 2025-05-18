<?php

declare(strict_types=1);

namespace Support\Classifier\Enums;

enum ClassifierTypeEnum: string
{
    case GENDER = 'gender';
    case CURRENCY = 'currency';
    case LANGUAGE = 'language';
    case LANGUAGE_LEVEL = 'language_level';
    case BENEFIT = 'benefit';
    case EMPLOYMENT_TYPE = 'employment_type';
    case EMPLOYMENT_FORM = 'employment_form';
    case SENIORITY = 'seniority';
    case EDUCATION_LEVEL = 'education_level';
    case FIELD = 'field';
    case PHONE_PREFIX = 'phone_prefix';
    case INTERVIEW_TYPE = 'interview_type';
    case TEST_TYPE = 'test_type';
    case REFUSAL_TYPE = 'refusal_type';
    case REJECTION_TYPE = 'rejection_type';
    case SALARY_FREQUENCY = 'salary_frequency';
    case SALARY_TYPE = 'salary_type';
    case DOCUMENT_TYPE = 'document_type';
    case DRIVING_LICENCE = 'driving_licence';
}
