<?php

declare(strict_types=1);

namespace Domain\Position\Jobs;

use App\Jobs\ScheduleJob;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionApprovalExpireUseCase;
use Illuminate\Database\Eloquent\Collection;

class ExpireApprovalProcessJob extends ScheduleJob
{
    public function handle(): void
    {
        Position::query()
            ->whereDate('approve_until', '<', now())
            ->where('approval_state', '=', PositionApprovalStateEnum::PENDING->value)
            ->chunkById(100, function (Collection $positions): void {
                /** @var Position $position */
                foreach ($positions as $position) {
                    PositionApprovalExpireUseCase::make()->handle($position);
                }
            }, 'positions.id');
    }
}
