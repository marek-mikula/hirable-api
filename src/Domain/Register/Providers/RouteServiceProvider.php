<?php

namespace Domain\Register\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/register')
                ->name('api.register.')
                ->group(__DIR__.'/../Routes/register.php');
        });
    }
}
