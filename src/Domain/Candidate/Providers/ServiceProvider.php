<?php

declare(strict_types=1);

namespace Domain\Candidate\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom([
            __DIR__.'/../Database/Migrations'
        ]);
    }
}
