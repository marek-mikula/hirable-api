<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum EvaluationResultEnum: string
{
    case RECOMMENDED = 'recommended';
    case NOT_RECOMMENDED = 'notRecommended';
    case NEUTRAL = 'neutral';
}
