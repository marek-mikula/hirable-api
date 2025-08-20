<?php

declare(strict_types=1);

namespace Domain\Candidate\Enums;

enum CandidateFieldEnum: string
{
    case FIRSTNAME = 'firstname';
    case LASTNAME = 'lastname';
    case GENDER = 'gender';
    case LANGUAGE = 'language';
    case EMAIL = 'email';
    case PHONE_PREFIX = 'phonePrefix';
    case PHONE_NUMBER = 'phoneNumber';
    case LINKEDIN = 'linkedin';
    case INSTAGRAM = 'instagram';
    case GITHUB = 'github';
    case PORTFOLIO = 'portfolio';
    case BIRTH_DATE = 'birthDate';
    case EXPERIENCE = 'experience';
    case TAGS = 'tags';
}
