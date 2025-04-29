<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Support\File\Schedule\DeleteDeletedFilesSchedule;

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
            $schedule->call(DeleteDeletedFilesSchedule::class)
                ->description('Deletes soft-deleted files from DB and disk.')
                ->dailyAt('00:00');

            return $schedule;
        });
    }
}
