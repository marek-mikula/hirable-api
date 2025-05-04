<?php

declare(strict_types=1);

namespace Support\Format\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Support\Format\Services\Formatter;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/format.php', 'format');

        $this->app->singleton(Formatter::class);
    }

    public function boot(): void
    {
        //
    }
}
