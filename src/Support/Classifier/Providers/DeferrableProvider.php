<?php

declare(strict_types=1);

namespace Support\Classifier\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\Classifier\Services\ClassifierConfigService;
use Support\Classifier\Services\ClassifierTranslateService;

class DeferrableProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(ClassifierConfigService::class);
        $this->app->singleton(ClassifierTranslateService::class);
    }

    public function provides(): array
    {
        return [
            ClassifierConfigService::class,
            ClassifierTranslateService::class,
        ];
    }
}
