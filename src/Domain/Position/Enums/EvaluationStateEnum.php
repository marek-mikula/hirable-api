<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum EvaluationStateEnum: string
{
    case WAITING = 'waiting';
    case FILLED = 'filled';
}
