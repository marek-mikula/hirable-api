<?php

declare(strict_types=1);

namespace Domain\Password\Listeners;

use App\Listeners\QueuedListener;
use Domain\Password\Events\PasswordChanged;
use Domain\Password\Notifications\PasswordChangedNotification;

class SendPasswordChangedEmailListener extends QueuedListener
{
    public function handle(PasswordChanged $event): void
    {
        $event->user->notify(new PasswordChangedNotification());
    }
}
