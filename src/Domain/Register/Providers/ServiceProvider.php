<?php

namespace Domain\Register\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'register');
    }
}
