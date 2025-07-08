<?php

declare(strict_types=1);

namespace Domain\Application\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/applications')
                ->name('api.applications.')
                ->group(__DIR__.'/../Routes/application.php');
        });
    }
}
