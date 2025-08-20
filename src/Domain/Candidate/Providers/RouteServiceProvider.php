<?php

declare(strict_types=1);

namespace Domain\Candidate\Providers;

use Domain\Candidate\Models\Candidate;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/candidates')
                ->name('api.candidates.')
                ->group(__DIR__.'/../Routes/candidate.php');
        });
    }

    private function bootModelBinding(): void
    {
        Route::model('candidate', Candidate::class);
    }
}
