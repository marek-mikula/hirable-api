<?php

declare(strict_types=1);

namespace Support\NotificationPreview\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\NotificationPreview\Services\NotificationRegistrar;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationRegistrar::class);
    }

    public function provides(): array
    {
        return [
            NotificationRegistrar::class,
        ];
    }
}
