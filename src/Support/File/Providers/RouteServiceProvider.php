<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Support\File\Models\File;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/files')
                ->name('api.files.')
                ->group(__DIR__.'/../Routes/file.php');
        });
    }

    private function bootModelBinding(): void
    {
        Route::model('file', File::class);
    }
}
