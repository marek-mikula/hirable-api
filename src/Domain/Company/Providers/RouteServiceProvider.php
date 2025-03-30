<?php

namespace Domain\Company\Providers;

use Domain\Company\Http\Middleware\CompanyRole;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->aliasMiddleware(CompanyRole::IDENTIFIER, CompanyRole::class);

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/company')
                ->name('api.company.')
                ->group(__DIR__.'/../Routes/company.php');
        });
    }
}
