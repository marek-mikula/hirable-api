<?php

declare(strict_types=1);

namespace Support\Classifier\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\Classifier\Cache\CachedClassifierRepositoryProxy;
use Support\Classifier\Cache\ClassifierCacheKeys;
use Support\Classifier\Repositories\ClassifierRepository;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;
use Support\Classifier\Services\ClassifierConfigService;
use Support\Classifier\Services\ClassifierSortService;
use Support\Classifier\Services\ClassifierTranslateService;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(ClassifierCacheKeys::class);

        $this->app->singleton(ClassifierSortService::class);
        $this->app->singleton(ClassifierConfigService::class);
        $this->app->singleton(ClassifierTranslateService::class);

        $this->app->bind(ClassifierRepositoryInterface::class, function () {
            /** @var ClassifierConfigService $config */
            $config = app(ClassifierConfigService::class);

            if ($config->isCacheEnabled()) {
                return app(CachedClassifierRepositoryProxy::class);
            }

            return app(ClassifierRepository::class);
        });
    }

    public function provides(): array
    {
        return [
            ClassifierCacheKeys::class,
            ClassifierSortService::class,
            ClassifierConfigService::class,
            ClassifierTranslateService::class,
            ClassifierRepositoryInterface::class,
        ];
    }
}
