<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionStateEnum: string
{
    case DRAFT = 'draft';
    case OPENED = 'opened';
    case CLOSED = 'closed';
    case CANCELED = 'canceled';
}
