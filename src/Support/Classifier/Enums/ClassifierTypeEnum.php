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
    case WORKLOAD = 'workload';
    case EMPLOYMENT_RELATIONSHIP = 'employment_relationship';
    case EMPLOYMENT_FORM = 'employment_form';
    case SENIORITY = 'seniority';
    case EDUCATION_LEVEL = 'education_level';
    case FIELD = 'field';
    case PHONE_PREFIX = 'phone_prefix';
    case INTERVIEW_TYPE = 'interview_type';
    case INTERVIEW_FORM = 'interview_form';
    case TEST_TYPE = 'test_type';
    case REFUSAL_REASON = 'refusal_reason';
    case REJECTION_REASON = 'rejection_reason';
    case SALARY_FREQUENCY = 'salary_frequency';
    case SALARY_TYPE = 'salary_type';
}
