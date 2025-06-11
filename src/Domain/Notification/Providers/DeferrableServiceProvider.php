<?php

declare(strict_types=1);

namespace Domain\Notification\Providers;

use Domain\Notification\Repositories\NotificationRepository;
use Domain\Notification\Repositories\NotificationRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }

    public function provides(): array
    {
        return [
            NotificationRepositoryInterface::class,
        ];
    }
}
