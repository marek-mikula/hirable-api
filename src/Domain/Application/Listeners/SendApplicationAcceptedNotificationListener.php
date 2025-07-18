<?php

declare(strict_types=1);

namespace Domain\Application\Listeners;

use App\Listeners\QueuedListener;
use Domain\Application\Events\ApplicationCreatedEvent;
use Domain\Application\Notifications\ApplicationAcceptedNotification;

class SendApplicationAcceptedNotificationListener extends QueuedListener
{
    public function handle(ApplicationCreatedEvent $event): void
    {
        $event->application->notify(new ApplicationAcceptedNotification());
    }
}
