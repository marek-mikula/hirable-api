<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Events\PositionApprovalEvent;
use Domain\Position\Models\Position;
use Domain\Position\Services\PositionApprovalService;

class SendToApprovalListener extends Listener
{
    public function __construct(
        private readonly PositionApprovalService $positionApprovalService,
    ) {
    }

    public function handle(PositionApprovalEvent $event): void
    {
        // do not trigger events, because otherwise
        // it would trigger observer infinite times
        Position::withoutEvents(function () use ($event): void {
            $this->positionApprovalService->sendForApproval($event->position->user, $event->position);
        });
    }
}
