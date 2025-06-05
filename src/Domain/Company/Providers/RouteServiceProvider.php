<?php

declare(strict_types=1);

namespace Domain\Company\Providers;

use Domain\Company\Http\Middleware\CompanyRoleMiddleware;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->aliasMiddleware(CompanyRoleMiddleware::IDENTIFIER, CompanyRoleMiddleware::class);

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/company')
                ->name('api.company.')
                ->group(__DIR__.'/../Routes/company.php');
        });
    }
}
