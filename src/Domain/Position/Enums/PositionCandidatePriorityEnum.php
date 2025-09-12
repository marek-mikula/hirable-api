<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionCandidatePriorityEnum: int
{
    case NONE = 0;
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
}
