<?php

namespace Support\Token\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Support\Token\Schedule\DeleteExpiredTokensSchedule;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->app->extend(Schedule::class, static function (Schedule $schedule): Schedule {
            $schedule->call(DeleteExpiredTokensSchedule::class)
                ->description('Deletes expired tokens from DB.')
                ->dailyAt('00:00');

            return $schedule;
        });
    }
}
