<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Events\PositionCandidateShareDeletedEvent;
use Domain\Position\Notifications\PositionCandidateShareStoppedNotification;

class SendPositionCandidateShareStoppedNotificationListener extends QueuedListener
{
    public function handle(PositionCandidateShareDeletedEvent $event): void
    {
        $event->user->notify(
            new PositionCandidateShareStoppedNotification($event->positionCandidate)
        );
    }
}
