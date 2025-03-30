<?php

namespace App\Providers;

use App\Enums\EnvEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeServiceProvider as BaseTelescopeServiceProvider;

class TelescopeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // register base package service provider
        $this->app->register(BaseTelescopeServiceProvider::class);

        // set dark mode
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry): bool {
            if (isEnv(EnvEnum::LOCAL)) {
                return true;
            }

            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });
    }

    public function boot(): void
    {
        Gate::define('viewTelescope', function (User $user): bool {
            return $user->email === (string) config('app.admin_email');
        });

        Telescope::auth(function (Request $request): bool {
            return isEnv(EnvEnum::LOCAL) || Gate::check('viewTelescope', [$request->user()]);
        });
    }

    private function hideSensitiveRequestDetails(): void
    {
        // show everything on local environment
        if (isEnv(EnvEnum::LOCAL)) {
            return;
        }

        Telescope::hideRequestParameters([
            'password',
            'passwordConfirm',
            'oldPassword',
        ]);

        Telescope::hideRequestHeaders([
            'xsrf-token',
        ]);
    }
}
