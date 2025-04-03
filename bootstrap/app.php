<?php

use App\Exceptions\ExceptionJsonHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders(
        providers: [
            App\Providers\AppServiceProvider::class,
            App\Providers\RepositoryServiceProvider::class,
            App\Providers\SupportServiceProvider::class,

            // Domains
            Domain\Auth\Providers\ServiceProvider::class,
            Domain\Register\Providers\ServiceProvider::class,
            Domain\Password\Providers\ServiceProvider::class,
            Domain\Verification\Providers\ServiceProvider::class,
            Domain\Company\Providers\ServiceProvider::class,
            Domain\Search\Providers\ServiceProvider::class,
            Domain\Candidate\Providers\ServiceProvider::class,
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
            'guest' => App\Http\Middleware\ProhibitIfAuthenticated::class,
        ]);

        // append middlewares to the global middleware stack
        $middleware->append(App\Http\Middleware\AddSecurityHeaders::class);
        $middleware->append(App\Http\Middleware\SetupActivityLogCauser::class);
        $middleware->append(App\Http\Middleware\SetLocale::class);

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

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e): bool {
            return $request->expectsJson();
        });

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
