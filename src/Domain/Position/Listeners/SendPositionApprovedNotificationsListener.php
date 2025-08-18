<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Events\PositionApprovedEvent;
use Domain\Position\Notifications\PositionApprovalApprovedNotification;

class SendPositionApprovedNotificationsListener extends QueuedListener
{
    public function handle(PositionApprovedEvent $event): void
    {
        $owner = $event->position->user;

        // send notification to owner of the position
        $owner->notify(new PositionApprovalApprovedNotification(
            position: $event->position,
        ));
    }
}
