<?php

declare(strict_types=1);

namespace Support\Format\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(DeferrableServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../Config/format.php', 'format');
    }

    public function boot(): void
    {
        //
    }
}
