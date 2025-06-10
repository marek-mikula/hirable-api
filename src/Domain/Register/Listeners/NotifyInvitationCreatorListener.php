<?php

declare(strict_types=1);

namespace Domain\Register\Listeners;

use App\Listeners\QueuedListener;
use Domain\Company\Notifications\InvitationAcceptedNotification;
use Domain\Register\Events\UserRegisteredInvitation;

class NotifyInvitationCreatorListener extends QueuedListener
{
    public function handle(UserRegisteredInvitation $event): void
    {
        $event->token->loadMissing('user')->user->notify(new InvitationAcceptedNotification($event->user));
    }
}
