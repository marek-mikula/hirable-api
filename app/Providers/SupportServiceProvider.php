<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\EnvEnum;
use Illuminate\Support\ServiceProvider;
use Support\ActivityLog\Providers\ServiceProvider as LogServiceProvider;
use Support\File\Providers\ServiceProvider as FileServiceProvider;
use Support\Grid\Providers\ServiceProvider as GridServiceProvider;
use Support\NotificationPreview\Providers\ServiceProvider as NotificationPreviewServiceProvider;
use Support\Setting\Providers\ServiceProvider as SettingServiceProvider;
use Support\Token\Providers\ServiceProvider as TokenServiceProvider;
use Support\Classifier\Providers\ServiceProvider as ClassifierServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!isEnv(EnvEnum::PRODUCTION, EnvEnum::TESTING)) {
            $this->app->register(NotificationPreviewServiceProvider::class);
        }

        $this->app->register(TokenServiceProvider::class);
        $this->app->register(LogServiceProvider::class);
        $this->app->register(FileServiceProvider::class);
        $this->app->register(GridServiceProvider::class);
        $this->app->register(SettingServiceProvider::class);
        $this->app->register(ClassifierServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
