<?php

declare(strict_types=1);

namespace Domain\AI\Providers;

use Domain\AI\Contracts\AIProviderInterface;
use Domain\AI\Services\AIConfigService;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(AIProviderInterface::class, fn () => app(AIConfigService::resolve()->getProviderClass()));
    }

    public function provides(): array
    {
        return [
            AIProviderInterface::class,
        ];
    }
}
