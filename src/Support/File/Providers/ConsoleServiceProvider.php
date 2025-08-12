<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Support\File\Schedule\DeleteDeletedFilesSchedule;
use Support\File\Schedule\DeleteHangingFilesSchedule;

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

            $schedule->call(DeleteHangingFilesSchedule::class)
                ->description('Deletes hanging files without any relationship.')
                ->monthly()
                ->at('00:00');

            // todo add script to check local storage

            return $schedule;
        });
    }
}
