<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Events\PositionCandidateCreatedEvent;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Notifications\PositionNewCandidateNotification;
use Domain\User\Models\User;

class SendNewCandidateNotificationListener extends QueuedListener
{
    public function handle(PositionCandidateCreatedEvent $event): void
    {
        $owner = $event->positionCandidate->position->user;

        $event->positionCandidate->position
            ->models()
            ->with('model')
            ->whereIn('role', [PositionRoleEnum::RECRUITER])
            ->get()
            ->map(fn (ModelHasPosition $modelHasPosition) => $modelHasPosition->model)
            ->add($owner)
            ->each(function (User $user) use ($event): void {
                $user->notify(new PositionNewCandidateNotification(
                    position: $event->positionCandidate->position,
                    candidate: $event->positionCandidate->candidate,
                ));
            });
    }
}
