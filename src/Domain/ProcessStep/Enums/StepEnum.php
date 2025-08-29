<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Enums;

enum StepEnum: string
{
    case NEW = 'new';
    case SCREENING = 'screening';
    case SHORTLIST = 'shortlist';
    case OFFER = 'offer';
    case PLACEMENT = 'placement';
    case REJECTED = 'rejected';
    case REFUSED = 'refused';
    case INTERVIEW = 'interview';
    case TEST = 'test';
    case TASK = 'task';
    case ASSESSMENT_CENTER = 'assessmentCenter';
}
