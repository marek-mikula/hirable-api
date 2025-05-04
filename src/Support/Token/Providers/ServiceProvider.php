<?php

declare(strict_types=1);

namespace Support\Token\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/token.php', 'token');

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(DeferrableProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../Database/Migrations'
        ]);
    }
}
