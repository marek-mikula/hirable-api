<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Models\PositionProcessStep;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/positions')
                ->name('api.positions.')
                ->group(__DIR__.'/../Routes/position.php');
        });
    }

    private function bootModelBinding(): void
    {
        Route::model('position', Position::class);
        Route::model('positionApproval', PositionApproval::class);
        Route::model('positionProcessStep', PositionProcessStep::class);
    }
}
