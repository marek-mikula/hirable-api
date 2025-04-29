<?php

declare(strict_types=1);

namespace Domain\Search\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/search')
                ->name('api.search.')
                ->group(__DIR__.'/../Routes/search.php');
        });
    }
}
