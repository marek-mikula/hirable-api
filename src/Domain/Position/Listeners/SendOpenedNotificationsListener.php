<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Notifications\PositionOpenedNotification;
use Domain\User\Models\User;

class SendOpenedNotificationsListener extends QueuedListener
{
    public function handle(PositionOpenedEvent $event): void
    {
        // send notifications to all hiring managers
        $event->position
            ->hiringManagers()
            ->get()
            ->each(function (User $model) use ($event): void {
                $model->notify(
                    new PositionOpenedNotification(
                        position: $event->position,
                    )
                );
            });
    }
}
