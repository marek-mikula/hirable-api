<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Enums;

enum StepEnum: string
{
    case NEW = 'new';
    case SCREENING = 'screening';
    case SHORTLIST = 'shortlist';
    case INTERVIEW = 'interview';
    case TEST = 'test';
    case TASK = 'task';
    case OFFER = 'offer';
    case ASSESSMENT_CENTER = 'assessmentCenter';
    case PLACEMENT = 'placement';
    case REJECTED = 'rejected';
}
