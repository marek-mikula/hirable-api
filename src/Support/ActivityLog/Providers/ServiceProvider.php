<?php

declare(strict_types=1);

namespace Support\ActivityLog\Providers;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Support\ActivityLog\Actions\SaveActivityLogsAction;
use Support\ActivityLog\Services\ActivityLogCauserResolver;
use Support\ActivityLog\Services\ActivityLogHandler;
use Support\ActivityLog\Services\ActivityLogManager;
use Support\ActivityLog\Services\ActivityLogSaver;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(DeferrableProvider::class);

        $this->app->singleton(ActivityLogCauserResolver::class);
        $this->app->singleton(ActivityLogHandler::class);
        $this->app->singleton(ActivityLogSaver::class);
        $this->app->singleton(ActivityLogManager::class);

        if ($this->app->runningInConsole()) {
            $this->registerConsoleHandler();
        } else {
            $this->registerHttpHandler();
        }
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../Database/Migrations'
        ]);
    }

    private function registerConsoleHandler(): void
    {
        $this->app->extend(ConsoleKernel::class, function (ConsoleKernel $kernel): ConsoleKernel {
            $kernel->whenCommandLifecycleIsLongerThan(0, [SaveActivityLogsAction::class, 'run']);

            return $kernel;
        });
    }

    private function registerHttpHandler(): void
    {
        $this->app->extend(HttpKernel::class, function (HttpKernel $kernel): HttpKernel {
            $kernel->whenRequestLifecycleIsLongerThan(0, [SaveActivityLogsAction::class, 'run']);

            return $kernel;
        });
    }
}
