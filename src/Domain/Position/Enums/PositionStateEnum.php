<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionStateEnum: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case CANCELED = 'canceled';
}
