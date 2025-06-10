<?php

declare(strict_types=1);

namespace Domain\Register\Providers;

use Domain\Register\Events\UserRegisteredInvitation;
use Domain\Register\Events\UserRegistered;
use Domain\Register\Listeners\NotifyInvitationCreatorListener;
use Domain\Register\Listeners\SendWelcomeEmailListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmailListener::class,
        ],
        UserRegisteredInvitation::class => [
            SendWelcomeEmailListener::class,
            NotifyInvitationCreatorListener::class,
        ],
    ];
}
