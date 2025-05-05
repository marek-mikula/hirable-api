<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\EnvEnum;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    private array $providers = [
        \Support\ActivityLog\Providers\ServiceProvider::class,
        \Support\File\Providers\ServiceProvider::class,
        \Support\Grid\Providers\ServiceProvider::class,
        \Support\Setting\Providers\ServiceProvider::class,
        \Support\Token\Providers\ServiceProvider::class,
        \Support\Classifier\Providers\ServiceProvider::class,
        \Support\Format\Providers\ServiceProvider::class,
        \Support\Notification\Providers\ServiceProvider::class,
    ];

    public function register(): void
    {
        if (!isEnv(EnvEnum::PRODUCTION, EnvEnum::TESTING)) {
            $this->app->register(\Support\NotificationPreview\Providers\ServiceProvider::class);
        }

        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot(): void
    {
        //
    }
}
