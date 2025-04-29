<?php

declare(strict_types=1);

namespace Support\Token\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\Token\Services\TokenResolver;

class DeferrableProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(TokenResolver::class);
    }

    public function provides(): array
    {
        return [
            TokenResolver::class,
        ];
    }
}
