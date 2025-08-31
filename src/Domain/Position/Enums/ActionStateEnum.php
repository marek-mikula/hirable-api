<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionStateEnum: string
{
    case ACTIVE = 'active';
    case FINISHED = 'finished';
    case CANCELED = 'canceled';

    /**
     * @return ActionStateEnum[]
     */
    public function getNextStates(): array
    {
        return match ($this) {
            self::ACTIVE => [self::FINISHED, self::CANCELED],
            self::FINISHED => [self::CANCELED],
            self::CANCELED => [self::FINISHED],
        };
    }
}
