<?php

declare(strict_types=1);

namespace Domain\Position\Schedules;

use App\Schedule\Schedule;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Jobs\ExpireApprovalProcessJob;
use Domain\Position\Models\Position;

class ExpireApprovalProcessSchedule extends Schedule
{
    public function __invoke(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        ExpireApprovalProcessJob::dispatch();
    }

    private function shouldRun(): bool
    {
        return Position::query()
            ->whereDate('approve_until', '<', now())
            ->where('approval_state', '=', PositionApprovalStateEnum::PENDING->value)
            ->exists();
    }
}
