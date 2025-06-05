<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Models;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;

use function Tests\Common\Helpers\assertCollectionsAreSame;

/** @covers \Domain\Position\Models\Builders\PositionApprovalBuilder::needsReminder */
it('tests needsReminder method', function (): void {
    $days = 4;

    config()->set('position.approval.remind_days', $days);

    $position = Position::factory()->create();
    $model = ModelHasPosition::factory()->ofPosition($position)->create();

    $approval1 = PositionApproval::factory()
        ->ofModelHasPosition($model)
        ->ofRemindedAt(null)
        ->ofCreatedAt(now()->subDays($days))
        ->ofState(PositionApprovalStateEnum::PENDING)
        ->create();
    $approval2 = PositionApproval::factory()
        ->ofModelHasPosition($model)
        ->ofRemindedAt(now()->subDays($days))
        ->ofCreatedAt(now()->subDays($days * 3))
        ->ofState(PositionApprovalStateEnum::PENDING)
        ->create();

    PositionApproval::factory()
        ->ofModelHasPosition($model)
        ->ofCreatedAt(now()->subDays($days)->addDay())
        ->ofState(PositionApprovalStateEnum::PENDING)
        ->create();

    PositionApproval::factory()
        ->ofModelHasPosition($model)
        ->ofRemindedAt(now()->subDays($days)->addDay())
        ->ofCreatedAt(now()->subDays($days * 3))
        ->ofState(PositionApprovalStateEnum::PENDING)
        ->create();

    $results = PositionApproval::query()->needsReminder()->get();

    assertCollectionsAreSame(collect([
        $approval1->id,
        $approval2->id,
    ]), $results);
});
