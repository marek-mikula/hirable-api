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

    public function getDefaultState(): ActionStateEnum
    {
        return match ($this) {
            self::REJECTION, self::START_OF_WORK => ActionStateEnum::FINISHED,
            default => ActionStateEnum::ACTIVE,
        };
    }
}
