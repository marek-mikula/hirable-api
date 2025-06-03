<?php

declare(strict_types=1);

namespace Domain\Position\Jobs;

use App\Jobs\ScheduleJob;
use Domain\Position\Models\PositionApproval;
use Domain\Position\UseCases\PositionApprovalNotifyUseCase;
use Illuminate\Database\Eloquent\Collection;

class NotifyApproversJob extends ScheduleJob
{
    public function handle(): void
    {
        PositionApproval::query()
            ->needsNotification()
            ->with(['position', 'modelHasPosition', 'modelHasPosition.model'])
            ->chunkById(100, function (Collection $approvals): void {
                PositionApprovalNotifyUseCase::make()->handle($approvals);
            }, 'position_approvals.id', 'id');
    }
}
