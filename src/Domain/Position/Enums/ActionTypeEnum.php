<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionTypeEnum: string
{
    case INTERVIEW = 'interview';
    case TEST = 'test';
    case TASK = 'task';
    case ASSESSMENT_CENTER = 'assessmentCenter';
    case OFFER = 'offer';
    case COMMUNICATION = 'communication';
    case REJECTION = 'rejection';
    case CUSTOM = 'custom';

    public function getDefaultState(): ActionStateEnum
    {
        return match ($this) {
            self::COMMUNICATION, self::REJECTION => ActionStateEnum::FINISHED,
            default => ActionStateEnum::ACTIVE,
        };
    }
}
