<?php

declare(strict_types=1);

namespace Domain\Register\Listeners;

use App\Listeners\QueuedListener;
use Domain\Register\Events\UserRegisteredInvitation;
use Domain\Register\Events\UserRegistered;
use Domain\Register\Notifications\RegisterRegisteredNotification;

class SendWelcomeEmailListener extends QueuedListener
{
    public function handle(UserRegistered|UserRegisteredInvitation $event): void
    {
        $event->user->notify(new RegisterRegisteredNotification());
    }
}
