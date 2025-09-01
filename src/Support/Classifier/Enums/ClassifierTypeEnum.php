<?php

declare(strict_types=1);

namespace Support\Classifier\Enums;

enum ClassifierTypeEnum: string
{
    case GENDER = 'gender';
    case CURRENCY = 'currency';
    case LANGUAGE = 'language';
    case LANGUAGE_LEVEL = 'languageLevel';
    case BENEFIT = 'benefit';
    case WORKLOAD = 'workload';
    case EMPLOYMENT_RELATIONSHIP = 'employmentRelationship';
    case EMPLOYMENT_FORM = 'employmentForm';
    case SENIORITY = 'seniority';
    case EDUCATION_LEVEL = 'educationLevel';
    case FIELD = 'field';
    case PHONE_PREFIX = 'phonePrefix';
    case INTERVIEW_TYPE = 'interviewType';
    case INTERVIEW_FORM = 'interviewForm';
    case TEST_TYPE = 'testType';
    case REFUSAL_REASON = 'refusalReason';
    case REJECTION_REASON = 'rejectionReason';
    case SALARY_FREQUENCY = 'salaryFrequency';
    case SALARY_TYPE = 'salaryType';
    case EMPLOYMENT_DURATION = 'employmentDuration';
}
