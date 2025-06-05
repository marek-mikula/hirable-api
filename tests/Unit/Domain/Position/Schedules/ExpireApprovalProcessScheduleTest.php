<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Schedules;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Jobs\ExpireApprovalProcessJob;
use Domain\Position\Models\Position;
use Domain\Position\Schedules\ExpireApprovalProcessSchedule;
use Illuminate\Support\Facades\Queue;

/** @covers \Domain\Position\Schedules\ExpireApprovalProcessSchedule::__invoke */
it('correctly dispatches job to expire approvals', function (): void {
    Queue::fake([
        ExpireApprovalProcessJob::class,
    ]);

    Position::factory()->ofApproveUntil(now()->subDay())->ofState(PositionStateEnum::APPROVAL_PENDING)->create();

    ExpireApprovalProcessSchedule::call();

    Queue::assertPushed(ExpireApprovalProcessJob::class);
});
