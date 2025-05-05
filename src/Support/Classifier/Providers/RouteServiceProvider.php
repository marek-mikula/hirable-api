<?php

declare(strict_types=1);

namespace Support\Classifier\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/classifiers')
                ->name('api.classifiers.')
                ->group(__DIR__.'/../Routes/classifier.php');
        });
    }
}
