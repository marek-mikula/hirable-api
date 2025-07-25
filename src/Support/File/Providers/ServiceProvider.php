<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(DeferrableServiceProvider::class);
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/file.php', 'file');

        $this->loadMigrationsFrom([
            __DIR__ . '/../Database/Migrations'
        ]);
    }
}
