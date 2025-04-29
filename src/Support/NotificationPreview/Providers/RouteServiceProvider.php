<?php

declare(strict_types=1);

namespace Support\NotificationPreview\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('web')
                ->prefix('notifications-preview')
                ->name('notification_preview.')
                ->group(__DIR__.'/../Routes/notification-preview.php');
        });
    }
}
