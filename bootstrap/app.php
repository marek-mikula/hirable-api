<?php

declare(strict_types=1);

use App\Exceptions\ExceptionJsonHandler;
use App\Http\Middleware\AddSecurityHeaders;
use App\Http\Middleware\ProhibitIfAuthenticated;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetupActivityLogCauser;
use App\Providers\AppServiceProvider;
use App\Providers\DomainServiceProvider;
use App\Providers\ServicesServiceProvider;
use App\Providers\SupportServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders(
        providers: [
            AppServiceProvider::class,
            SupportServiceProvider::class,
            DomainServiceProvider::class,
            ServicesServiceProvider::class,
        ],

        // do not autoload providers from /bootstrap/providers.php path
        withBootstrapProviders: false
    )
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/status',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // configure api:
        // - turn on stateful Sanctum middleware
        // - add throttling
        $middleware->statefulApi();
        $middleware->throttleApi();

        // alias middlewares
        $middleware->alias([
            'guest' => ProhibitIfAuthenticated::class,
        ]);

        // append middlewares to the global middleware stack
        $middleware->append(AddSecurityHeaders::class);
        $middleware->append(SetupActivityLogCauser::class);
        $middleware->append(SetLocale::class);

        // configure trim strings middleware to ignore some inputs
        $middleware->trimStrings([
            'password',
            'oldPassword',
            'passwordConfirm',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontFlash([
            'password',
            'oldPassword',
            'passwordConfirm',
        ]);

        $exceptions->shouldRenderJsonWhen(fn (Request $request, Throwable $e): bool => $request->expectsJson());

        // define render process
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return (new ExceptionJsonHandler())->handle($e, $request);
            }

            // if closure returns nothing, Laravel default
            // render process will be used
        });
    })
    ->create();
