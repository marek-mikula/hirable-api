<?php

declare(strict_types=1);

namespace Domain\Position\Schedules;

use App\Schedule\Schedule;
use Domain\Position\Jobs\RemindEvaluationUsersJob;
use Domain\Position\Models\PositionCandidateEvaluation;

class RemindEvaluationUsersSchedule extends Schedule
{
    public function __invoke(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        RemindEvaluationUsersJob::dispatch();
    }

    private function shouldRun(): bool
    {
        return PositionCandidateEvaluation::query()
            ->waiting()
            ->needsReminder()
            ->exists();
    }
}
