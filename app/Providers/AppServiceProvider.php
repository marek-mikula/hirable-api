<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\EnvEnum;
use App\Services\Formatter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (telescopeEnabled()) {
            $this->app->register(TelescopeServiceProvider::class);
        }

        if (isEnv(EnvEnum::TESTING)) {
            $this->loadTestingMigrations();
        }

        $this->app->singleton(Formatter::class);
    }

    public function boot(): void
    {
        $this->bootApiRateLimiter();

        // disable lazy loading on non-prod servers
        Model::preventLazyLoading(!isEnv(EnvEnum::PRODUCTION));
    }

    private function bootApiRateLimiter(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }

    private function loadTestingMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../tests/Common/Database/Migrations');
    }
}
