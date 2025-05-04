<?php

declare(strict_types=1);

namespace Support\Notification\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom([
            __DIR__.'/../Database/Migrations'
        ]);
    }
}
