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

    public function getNextStates(): array
    {
        return match ($this) {
            self::DRAFT => [
                self::APPROVAL_PENDING,
                self::OPENED,
            ],
            self::APPROVAL_PENDING => [
                self::APPROVAL_APPROVED,
                self::APPROVAL_REJECTED,
                self::APPROVAL_CANCELED,
                self::APPROVAL_EXPIRED,
            ],
            self::APPROVAL_APPROVED, => [
                self::OPENED,
            ],
            self::APPROVAL_REJECTED, self::APPROVAL_EXPIRED, self::APPROVAL_CANCELED => [
                self::DRAFT,
                self::APPROVAL_PENDING,
                self::OPENED,
            ],
            self::OPENED => [
                self::CLOSED,
                self::CANCELED
            ],
            self::CLOSED, self::CANCELED => [],
        };
    }
}
