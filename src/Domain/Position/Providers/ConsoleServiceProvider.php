<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Schedules\ExpireApprovalProcessSchedule;
use Domain\Position\Schedules\RemindApproversSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

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
            $schedule->call(ExpireApprovalProcessSchedule::class)
                ->description('Expires all pending approval processes if they take too long.')
                ->dailyAt('00:00');

            $schedule->call(RemindApproversSchedule::class)
                ->description('Notifies approvers that have not decided yet on their approval.')
                ->dailyAt('06:00');

            return $schedule;
        });
    }
}
