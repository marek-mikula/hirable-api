<?php

declare(strict_types=1);

namespace Support\Format\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\Format\Services\Formatter;

class DeferrableProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(Formatter::class);
    }

    public function provides(): array
    {
        return [
            Formatter::class,
        ];
    }
}
