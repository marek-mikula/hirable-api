<?php

declare(strict_types=1);

namespace Support\NotificationPreview\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Support\NotificationPreview\Console\Commands\CheckCommand;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(DeferrableProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'notifications-preview');

        $this->commands([
            CheckCommand::class,
        ]);
    }
}
