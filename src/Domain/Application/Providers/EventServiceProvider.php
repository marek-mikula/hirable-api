<?php

declare(strict_types=1);

namespace Domain\Application\Providers;

use Domain\Application\Events\ApplicationCreatedEvent;
use Domain\Application\Models\Application;
use Domain\Application\Observers\ApplicationObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ApplicationCreatedEvent::class => [],
    ];

    protected $observers = [
        Application::class => ApplicationObserver::class
    ];
}
