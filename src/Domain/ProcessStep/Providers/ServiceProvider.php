<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(DeferrableServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/processStep.php', 'process_step');

        $this->loadMigrationsFrom([
            __DIR__.'/../Database/Migrations'
        ]);
    }
}
