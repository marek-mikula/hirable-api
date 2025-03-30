<?php

namespace Support\ActivityLog\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\ActivityLog\Services\ActivityLogCauserResolver;
use Support\ActivityLog\Services\ActivityLogHandler;
use Support\ActivityLog\Services\ActivityLogManager;
use Support\ActivityLog\Services\ActivityLogSaver;

class DeferrableProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(ActivityLogCauserResolver::class);
        $this->app->singleton(ActivityLogHandler::class);
        $this->app->singleton(ActivityLogSaver::class);
        $this->app->singleton(ActivityLogManager::class);
    }

    public function provides(): array
    {
        return [
            ActivityLogCauserResolver::class,
            ActivityLogHandler::class,
            ActivityLogSaver::class,
            ActivityLogManager::class,
        ];
    }
}
