<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionAssessmentCenterResultEnum: string
{
    case NO_SHOW = 'noShow';
    case EXCUSED = 'excused';
    case OK = 'ok';
    case OTHER = 'other';
}
