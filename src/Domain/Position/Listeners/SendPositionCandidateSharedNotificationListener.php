<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Events\PositionCandidateShareCreatedEvent;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Notifications\PositionCandidateSharedNotification;
use Domain\User\Models\User;

class SendPositionCandidateSharedNotificationListener extends QueuedListener
{
    public function handle(PositionCandidateShareCreatedEvent $event): void
    {
        /** @var User $user */
        $user = User::query()->findOrFail($event->positionCandidateShare->user_id);

        /** @var PositionCandidate $positionCandidate */
        $positionCandidate = PositionCandidate::query()->findOrFail($event->positionCandidateShare->position_candidate_id);

        $user->notify(new PositionCandidateSharedNotification($positionCandidate));
    }
}
