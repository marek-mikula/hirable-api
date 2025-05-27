<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/positions')
                ->name('api.positions.')
                ->group(__DIR__.'/../Routes/position.php');
        });
    }
}
