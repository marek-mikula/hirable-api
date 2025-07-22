<?php

declare(strict_types=1);

namespace Domain\AI\Providers;

use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Services\AIConfigService;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(AIServiceInterface::class, function () {
            return app(AIConfigService::resolve()->getServiceClass());
        });
    }

    public function provides(): array
    {
        return [
            AIServiceInterface::class,
        ];
    }
}
