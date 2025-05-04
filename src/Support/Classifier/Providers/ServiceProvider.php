<?php

declare(strict_types=1);

namespace Support\Classifier\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/classifier.php', 'classifier');

        $this->app->register(DeferrableProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../Database/Migrations'
        ]);
    }
}
