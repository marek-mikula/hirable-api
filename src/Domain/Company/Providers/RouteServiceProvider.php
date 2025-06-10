<?php

declare(strict_types=1);

namespace Domain\Company\Providers;

use Domain\Company\Http\Middleware\CompanyRoleMiddleware;
use Domain\Company\Models\Company;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();

        $this->aliasMiddleware(CompanyRoleMiddleware::IDENTIFIER, CompanyRoleMiddleware::class);

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/company')
                ->name('api.company.')
                ->group(__DIR__.'/../Routes/company.php');
        });
    }

    private function bootModelBinding(): void
    {
        Route::model('company', Company::class);
    }
}
