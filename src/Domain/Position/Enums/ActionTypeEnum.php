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
    case REFUSAL = 'refusal';
    case CUSTOM = 'custom';

    /**
     * @return ActionStateEnum[]
     */
    public function getAllowedStates(): array
    {
        return match ($this) {
            self::INTERVIEW, self::ASSESSMENT_CENTER, self::TEST, self::TASK, self::OFFER => [
                ActionStateEnum::CREATED,
                ActionStateEnum::SENT,
                ActionStateEnum::CANCELED,
            ],
            self::COMMUNICATION, self::REJECTION, self::CUSTOM => [
                ActionStateEnum::CREATED,
                ActionStateEnum::SENT,
            ],
            self::REFUSAL => [
                ActionStateEnum::CREATED,
            ],
        };
    }
}
