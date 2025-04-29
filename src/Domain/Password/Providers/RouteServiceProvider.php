<?php

declare(strict_types=1);

namespace Domain\Password\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/password')
                ->name('api.password.')
                ->group(__DIR__.'/../Routes/password.php');
        });
    }
}
