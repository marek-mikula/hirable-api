<?php

namespace Support\File\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(ConsoleServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
