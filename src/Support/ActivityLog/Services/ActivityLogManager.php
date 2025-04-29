<?php

declare(strict_types=1);

namespace Support\ActivityLog\Services;

use Illuminate\Database\Eloquent\Model;
use Support\ActivityLog\Data\Log;
use Support\ActivityLog\Data\LogBuilder;

class ActivityLogManager
{
    // if activity logging is enabled globally
    private bool $enabled = true;

    /** @var Log[] */
    private array $logs = [];

    // this number of logs gets saved instantly
    private int $asyncThreshold = 10;

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function withoutActivityLogs(callable $callback): mixed
    {
        $previousState = $this->isEnabled();

        $this->disable();

        $result = call_user_func($callback);

        $this->enabled = $previousState;

        return $result;
    }

    public function log(Model $subject, string $action): LogBuilder
    {
        return LogBuilder::create(subject: $subject, action: $action);
    }

    public function addLog(Log $log): void
    {
        $this->logs[] = $log;
    }

    public function dumpLogs(): void
    {
        $this->logs = [];
    }

    /**
     * @return Log[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * @return Log[]
     */
    public function pullLogs(): array
    {
        $logs = $this->getLogs();

        $this->dumpLogs();

        return $logs;
    }

    public function setAsyncThreshold(int $threshold): void
    {
        $this->asyncThreshold = $threshold;
    }

    public function getAsyncThreshold(): int
    {
        return $this->asyncThreshold;
    }
}
