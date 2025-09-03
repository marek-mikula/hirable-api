<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionInterviewResultEnum: string
{
    case UNAVAILABLE = 'unavailable';
    case NO_SHOW = 'noShow';
    case EXCUSED = 'excused';
    case OK = 'ok';
    case OTHER = 'other';
}
