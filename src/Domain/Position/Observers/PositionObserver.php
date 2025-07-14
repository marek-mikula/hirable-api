<?php

declare(strict_types=1);

namespace Domain\Position\Observers;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Events\PositionApprovalEvent;
use Domain\Position\Events\PositionApprovalExpiredEvent;
use Domain\Position\Events\PositionApprovedEvent;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Events\PositionRejectedEvent;
use Domain\Position\Models\Position;

class PositionObserver
{
    public function created(Position $position): void
    {
        //
    }

    public function updated(Position $position): void
    {
        if ($position->wasChanged('state') && $position->state === PositionStateEnum::OPENED) {
            PositionOpenedEvent::dispatch($position);
        } elseif ($position->wasChanged('state') && $position->state === PositionStateEnum::APPROVAL_PENDING) {
            PositionApprovalEvent::dispatch($position);
        } elseif ($position->wasChanged('state') && $position->state === PositionStateEnum::APPROVAL_CANCELED) {
            PositionApprovalCanceledEvent::dispatch($position);
        } elseif ($position->wasChanged('state') && $position->state === PositionStateEnum::APPROVAL_EXPIRED) {
            PositionApprovalExpiredEvent::dispatch($position);
        } elseif ($position->wasChanged('state') && $position->state === PositionStateEnum::APPROVAL_APPROVED) {
            PositionApprovedEvent::dispatch($position);
        } elseif ($position->wasChanged('state') && $position->state === PositionStateEnum::APPROVAL_REJECTED) {
            PositionRejectedEvent::dispatch($position);
        }
    }

    public function deleted(Position $position): void
    {
        //
    }

    public function restored(Position $position): void
    {
        //
    }

    public function forceDeleted(Position $position): void
    {
        //
    }
}
