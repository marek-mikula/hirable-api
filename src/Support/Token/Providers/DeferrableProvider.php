<?php

declare(strict_types=1);

namespace Support\Token\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\Token\Repositories\TokenRepository;
use Support\Token\Repositories\TokenRepositoryInterface;
use Support\Token\Services\TokenResolver;

class DeferrableProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(TokenResolver::class);

        $this->app->bind(TokenRepositoryInterface::class, TokenRepository::class);
    }

    public function provides(): array
    {
        return [
            TokenResolver::class,
            TokenRepositoryInterface::class,
        ];
    }
}
