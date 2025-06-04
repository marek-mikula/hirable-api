<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionStateEnum: string
{
    case DRAFT = 'draft';
    case APPROVAL_PENDING = 'approvalPending';
    case APPROVAL_APPROVED = 'approvalApproved';
    case APPROVAL_REJECTED = 'approvalRejected';
    case APPROVAL_CANCELED = 'approvalCanceled';
    case APPROVAL_EXPIRED = 'approvalExpired';
    case OPENED = 'opened';
    case CLOSED = 'closed';
    case CANCELED = 'canceled';
}
