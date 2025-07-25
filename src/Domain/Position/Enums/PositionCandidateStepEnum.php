<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionCandidateStepEnum: string
{
    // fixed states
    case NEW = 'new';
    case SCREENING = 'screening';
    case SHORTLIST = 'shortlist';
    case OFFER_SENT = 'offerSent';
    case OFFER_ACCEPTED = 'offerAccepted';
    case PLACEMENT = 'placement';
    case REJECTED = 'rejected';
    case WITHDRAWN = 'withdrawn';

    // toggleable states
    case INTERVIEW = 'interview';
    case TEST = 'test';
    case TASK = 'task';
    case ASSESSMENT_CENTER = 'assessmentCenter';
    case BACKGROUND_CHECK = 'backgroundCheck';
    case REFERENCE_CHECK = 'referenceCheck';
}
