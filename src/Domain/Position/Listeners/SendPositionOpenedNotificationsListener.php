<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Notifications\PositionOpenedNotification;
use Domain\User\Models\User;

class SendPositionOpenedNotificationsListener extends QueuedListener
{
    public function handle(PositionOpenedEvent $event): void
    {
        $event->position
            ->models()
            ->with('model')
            ->whereIn('role', [PositionRoleEnum::RECRUITER, PositionRoleEnum::HIRING_MANAGER])
            ->get()
            ->map(fn (ModelHasPosition $modelHasPosition) => $modelHasPosition->model)
            ->each(function (User $user) use ($event): void {
                $user->notify(new PositionOpenedNotification(position: $event->position));
            });
    }
}
