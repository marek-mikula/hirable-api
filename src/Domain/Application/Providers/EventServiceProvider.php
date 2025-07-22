<?php

declare(strict_types=1);

namespace Domain\Application\Providers;

use Domain\Application\Events\ApplicationCreatedEvent;
use Domain\Application\Events\ApplicationProcessedEvent;
use Domain\Application\Listeners\SendApplicationAcceptedNotificationListener;
use Domain\Application\Listeners\ProcessApplicationListener;
use Domain\Application\Listeners\SendNewCandidateNotificationListener;
use Domain\Application\Models\Application;
use Domain\Application\Observers\ApplicationObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ApplicationCreatedEvent::class => [
            ProcessApplicationListener::class,
            SendApplicationAcceptedNotificationListener::class,
        ],
        ApplicationProcessedEvent::class => [
            SendNewCandidateNotificationListener::class,
        ],
    ];

    protected $observers = [
        Application::class => ApplicationObserver::class
    ];
}
