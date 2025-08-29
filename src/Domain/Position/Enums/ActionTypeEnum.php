<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionTypeEnum: string
{
    case INTERVIEW = 'interview';
    case TEST = 'test';
    case TASK = 'task';
    case ASSESSMENT_CENTER = 'assessmentCenter';
}
