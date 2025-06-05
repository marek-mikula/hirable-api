<?php

declare(strict_types=1);

namespace Domain\Position\Jobs;

use App\Jobs\ScheduleJob;
use Domain\Position\Models\PositionApproval;
use Domain\Position\UseCases\PositionApprovalRemindUseCase;
use Illuminate\Database\Eloquent\Collection;

class RemindApproversJob extends ScheduleJob
{
    public function handle(): void
    {
        PositionApproval::query()
            ->needsReminder()
            ->with([
                'position',
                'modelHasPosition',
                'modelHasPosition.model',
                'token',
            ])
            ->chunkById(100, function (Collection $approvals): void {
                PositionApprovalRemindUseCase::make()->handle($approvals);
            }, 'position_approvals.id', 'id');
    }
}
