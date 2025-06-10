<?php

declare(strict_types=1);

namespace Domain\Company\Providers;

use Domain\Company\Http\Middleware\CompanyRoleMiddleware;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Support\Token\Models\Token;

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
        Route::model('contact', CompanyContact::class);
        Route::model('invitation', Token::class);
    }
}
