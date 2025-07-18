<?php

declare(strict_types=1);

namespace Domain\AI\Providers;

use Domain\AI\Contracts\AIServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(AIServiceInterface::class, function () {
            $service = (string) config('ai.service');
            $services = (array) config('ai.services');

            throw_if(!array_key_exists($service, $services), new \Exception(sprintf('Undefined AI service %s given.', $service)));

            return app($services[$service]);
        });
    }

    public function provides(): array
    {
        return [
            AIServiceInterface::class,
        ];
    }
}
