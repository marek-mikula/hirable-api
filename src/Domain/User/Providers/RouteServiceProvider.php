<?php

declare(strict_types=1);

namespace Domain\User\Providers;

use Domain\User\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/users')
                ->name('api.users.')
                ->group(__DIR__.'/../Routes/user.php');
        });
    }

    private function bootModelBinding(): void
    {
        Route::model('user', User::class);
    }
}
