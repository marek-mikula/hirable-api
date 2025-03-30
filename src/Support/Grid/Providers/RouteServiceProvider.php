<?php

namespace Support\Grid\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/grids')
                ->name('api.grid.')
                ->group(__DIR__.'/../Routes/grid.php');
        });
    }
}
