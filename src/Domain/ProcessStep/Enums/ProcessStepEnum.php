<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Enums;

enum ProcessStepEnum: string
{
    // fixed steps
    case NEW = 'new';
    case SCREENING = 'screening';
    case SHORTLIST = 'shortlist';
    case OFFER_SENT = 'offerSent';
    case OFFER_ACCEPTED = 'offerAccepted';
    case PLACEMENT = 'placement';
    case REJECTED = 'rejected';
    case WITHDRAWN = 'withdrawn';

    // toggleable steps
    case INTERVIEW = 'interview';
    case TEST = 'test';
    case TASK = 'task';
    case ASSESSMENT_CENTER = 'assessmentCenter';
    case BACKGROUND_CHECK = 'backgroundCheck';
    case REFERENCE_CHECK = 'referenceCheck';

    public function isMultistep(): bool
    {
        return match ($this) {
            self::INTERVIEW,
            self::TEST,
            self::TASK,
            self::ASSESSMENT_CENTER,
            self::BACKGROUND_CHECK,
            self::REFERENCE_CHECK => true,
            default => false,
        };
    }
}
