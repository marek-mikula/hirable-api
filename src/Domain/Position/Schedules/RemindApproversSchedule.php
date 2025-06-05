<?php

declare(strict_types=1);

namespace Domain\Position\Schedules;

use App\Schedule\Schedule;
use Domain\Position\Jobs\RemindApproversJob;
use Domain\Position\Models\PositionApproval;

class RemindApproversSchedule extends Schedule
{
    public function __invoke(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        RemindApproversJob::dispatch();
    }

    private function shouldRun(): bool
    {
        return PositionApproval::query()
            ->needsReminder()
            ->exists();
    }
}
