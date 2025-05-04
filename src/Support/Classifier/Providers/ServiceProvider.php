<?php

declare(strict_types=1);

namespace Support\Classifier\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Support\Classifier\Services\ClassifierConfigService;
use Support\Classifier\Services\ClassifierTranslateService;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/classifier.php', 'classifier');

        $this->app->singleton(ClassifierConfigService::class);
        $this->app->singleton(ClassifierTranslateService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../Database/Migrations'
        ]);
    }
}
