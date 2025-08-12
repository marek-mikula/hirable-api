<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Providers;

use Domain\ProcessStep\Models\ProcessStep;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/process-steps')
                ->name('api.process_steps.')
                ->group(__DIR__.'/../Routes/processStep.php');
        });
    }

    private function bootModelBinding(): void
    {
        Route::model('processStep', ProcessStep::class);
    }
}
