<?php

declare(strict_types=1);

namespace Support\Setting\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(DeferrableProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
