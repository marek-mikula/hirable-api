<?php

namespace Support\Token\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Support\Token\Http\Middleware\TokenMiddleware;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->aliasMiddleware(TokenMiddleware::IDENTIFIER, TokenMiddleware::class);
    }
}
