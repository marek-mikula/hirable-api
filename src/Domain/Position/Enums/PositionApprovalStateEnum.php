<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionApprovalStateEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REJECTED_HM = 'rejectedHm';
}
