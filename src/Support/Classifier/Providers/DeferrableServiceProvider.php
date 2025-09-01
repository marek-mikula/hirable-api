<?php

declare(strict_types=1);

namespace Support\Classifier\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\Classifier\Repositories\ClassifierCachedRepository;
use Support\Classifier\Repositories\ClassifierRepository;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;
use Support\Classifier\Services\ClassifierConfigService;
use Support\Classifier\Services\ClassifierSortService;
use Support\Classifier\Services\ClassifierTranslateService;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(ClassifierSortService::class);
        $this->app->singleton(ClassifierConfigService::class);
        $this->app->singleton(ClassifierTranslateService::class);

        $this->app->bind(ClassifierRepositoryInterface::class, function () {
            if (ClassifierConfigService::resolve()->isCacheEnabled()) {
                return app(ClassifierCachedRepository::class);
            }

            return app(ClassifierRepository::class);
        });
    }

    public function provides(): array
    {
        return [
            ClassifierSortService::class,
            ClassifierConfigService::class,
            ClassifierTranslateService::class,
            ClassifierRepositoryInterface::class,
        ];
    }
}
