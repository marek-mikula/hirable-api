<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionCandidatePriorityEnum: int
{
    case LOW = 1;
    case HIGH = 2;
    case HIGHEST = 3;
}
