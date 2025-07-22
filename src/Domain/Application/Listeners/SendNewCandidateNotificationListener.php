<?php

declare(strict_types=1);

namespace Domain\Application\Listeners;

use App\Listeners\QueuedListener;
use Domain\Application\Events\ApplicationProcessedEvent;
use Domain\Application\Notifications\ApplicationNewCandidateNotification;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\User\Models\User;

class SendNewCandidateNotificationListener extends QueuedListener
{
    public function handle(ApplicationProcessedEvent $event): void
    {
        $owner = $event->application->position->user;

        $event->application->position
            ->models()
            ->with('model')
            ->whereIn('role', [PositionRoleEnum::RECRUITER])
            ->get()
            ->map(fn (ModelHasPosition $modelHasPosition) => $modelHasPosition->model)
            ->add($owner)
            ->each(function (User $user) use ($event): void {
                $user->notify(new ApplicationNewCandidateNotification(
                    position: $event->application->position,
                    candidate: $event->application->candidate,
                ));
            });
    }
}
