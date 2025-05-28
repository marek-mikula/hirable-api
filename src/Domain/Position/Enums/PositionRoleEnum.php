<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionRoleEnum: string
{
    case HIRING_MANAGER = 'hiringManager';
    case APPROVER = 'approver';
}
