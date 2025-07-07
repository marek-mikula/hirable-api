<?php

declare(strict_types=1);

namespace Domain\Position\Observers;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionApprovalApprovedEvent;
use Domain\Position\Events\PositionApprovalRejectedEvent;
use Domain\Position\Models\PositionApproval;

class PositionApprovalObserver
{
    public function created(PositionApproval $positionApproval): void
    {
        //
    }

    public function updated(PositionApproval $positionApproval): void
    {
        if ($positionApproval->wasChanged('state') && $positionApproval->state === PositionApprovalStateEnum::APPROVED) {
            PositionApprovalApprovedEvent::dispatch($positionApproval);
        } elseif ($positionApproval->wasChanged('state') && $positionApproval->state === PositionApprovalStateEnum::REJECTED) {
            PositionApprovalRejectedEvent::dispatch($positionApproval);
        }
    }

    public function deleted(PositionApproval $positionApproval): void
    {
        //
    }

    public function restored(PositionApproval $positionApproval): void
    {
        //
    }

    public function forceDeleted(PositionApproval $positionApproval): void
    {
        //
    }
}
