<?php

declare(strict_types=1);

namespace Domain\Notification\Providers;

use Domain\Notification\Models\Notification;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/notifications')
                ->name('api.notifications.')
                ->group(__DIR__.'/../Routes/notification.php');
        });
    }

    private function bootModelBinding(): void
    {
        Route::model('notification', Notification::class);
    }
}
