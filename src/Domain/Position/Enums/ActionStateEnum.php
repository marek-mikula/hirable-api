<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionStateEnum: string
{
    // action was created in the system
    case CREATED = 'created';

    // action was sent to the candidate
    case SENT = 'sent';

    // action is finished
    case FINISHED = 'finished';

    // user or candidate canceled the action
    case CANCELED = 'canceled';

    /**
     * @return ActionStateEnum[]
     */
    public function getNextStates(): array
    {
        return match ($this) {
            self::CREATED => [
                self::SENT,
                self::FINISHED,
                self::CANCELED,
            ],
            self::SENT => [
                self::FINISHED,
                self::CANCELED,
            ],
            self::FINISHED, self::CANCELED => [],
        };
    }
}
