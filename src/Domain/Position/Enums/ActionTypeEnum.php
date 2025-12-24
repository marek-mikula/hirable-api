<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionTypeEnum: string
{
    case INTERVIEW = 'interview';
    case TASK = 'task';
    case ASSESSMENT_CENTER = 'assessmentCenter';
    case OFFER = 'offer';
    case REJECTION = 'rejection';
    case START_OF_WORK = 'startOfWork';
    case CUSTOM = 'custom';
}
