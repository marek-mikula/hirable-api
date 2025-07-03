<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionRoleEnum: string
{
    case RECRUITER = 'recruiter';
    case HIRING_MANAGER = 'hiringManager';
    case APPROVER = 'approver';
    case EXTERNAL_APPROVER = 'externalApprover';
}
