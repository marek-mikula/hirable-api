<?php

declare(strict_types=1);

namespace Domain\Verification\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/verification')
                ->name('api.verification.')
                ->group(__DIR__.'/../Routes/verification.php');
        });
    }
}
