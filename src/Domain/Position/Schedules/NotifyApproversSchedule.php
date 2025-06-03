<?php

declare(strict_types=1);

namespace Domain\Position\Schedules;

use App\Schedule\Schedule;
use Domain\Position\Jobs\NotifyApproversJob;
use Domain\Position\Models\PositionApproval;

class NotifyApproversSchedule extends Schedule
{
    public function __invoke(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        NotifyApproversJob::dispatch();
    }

    private function shouldRun(): bool
    {
        return PositionApproval::query()
            ->needsNotification()
            ->exists();
    }
}
