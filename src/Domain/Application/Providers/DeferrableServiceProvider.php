<?php

declare(strict_types=1);

namespace Domain\Application\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
    }

    public function provides(): array
    {
        return [];
    }
}
