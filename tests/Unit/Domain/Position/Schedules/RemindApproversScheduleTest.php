<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Schedules;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Jobs\RemindApproversJob;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Schedules\RemindApproversSchedule;
use Illuminate\Support\Facades\Queue;

/** @covers \Domain\Position\Schedules\RemindApproversSchedule::__invoke */
it('correctly dispatches job to notify approvals', function (): void {
    Queue::fake([
        RemindApproversJob::class,
    ]);

    $remindDays = 4;

    config()->set('position.approval.remind_days', $remindDays);

    $position = Position::factory()->create();
    $model = ModelHasPosition::factory()->ofPosition($position)->create();

    PositionApproval::factory()
        ->ofModelHasPosition($model)
        ->ofState(PositionApprovalStateEnum::PENDING)
        ->ofCreatedAt(now()->subDays($remindDays))
        ->ofRemindedAt(null)
        ->create();

    RemindApproversSchedule::call();

    Queue::assertPushed(RemindApproversJob::class);
});
