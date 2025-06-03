<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(DeferrableServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(ConsoleServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'position');

        $this->loadMigrationsFrom([
            __DIR__.'/../Database/Migrations'
        ]);
    }
}
