<?php

namespace Support\ActivityLog\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Support\ActivityLog\Data\Log;
use Support\ActivityLog\Data\LogBuilder;
use Support\ActivityLog\Services\ActivityLogManager;

/**
 * @method static void enable() enables activity log globally
 * @method static void disable() disables activity log globally
 * @method static bool isEnabled() checks if activity log is enabled globally
 * @method static mixed withoutActivityLogs(callable $callback) calls given callback without activity logging
 * @method static LogBuilder log(Model $subject, string $action) creates log build instance to add custom log
 * @method static void addLog(Log $log) adds log to the log stack
 * @method static void dumpLogs() dumps all logs in the log stack
 * @method static Log[] getLogs() gets all logs in the log stack
 * @method static Log[] pullLogs() gets all logs in the log stack and dumps it
 * @method static void setAsyncThreshold(int $threshold) set number of logs that will get saved instantly
 * @method static int getAsyncThreshold() get number of logs that will get saved instantly
 */
class ActivityLog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogManager::class;
    }
}
