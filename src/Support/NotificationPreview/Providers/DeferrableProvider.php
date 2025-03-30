<?php

namespace Support\NotificationPreview\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\NotificationPreview\Services\NotificationRegistrar;

class DeferrableProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(NotificationRegistrar::class);
    }

    public function provides(): array
    {
        return [
            NotificationRegistrar::class,
        ];
    }
}
