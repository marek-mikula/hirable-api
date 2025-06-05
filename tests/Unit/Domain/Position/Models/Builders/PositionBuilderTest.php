<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Models\Builders;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;

use function Tests\Common\Helpers\assertCollectionsAreSame;

/** @covers \Domain\Position\Models\Builders\PositionBuilder::approvalExpired */
it('tests approvalExpired method', function (): void {
    Position::factory()->ofApproveUntil(now())->ofState(PositionStateEnum::APPROVAL_PENDING)->create();
    Position::factory()->ofApproveUntil(now()->subDay())->ofState(PositionStateEnum::APPROVAL_APPROVED)->create();
    $position = Position::factory()->ofApproveUntil(now()->subDay())->ofState(PositionStateEnum::APPROVAL_PENDING)->create();

    $results = Position::query()->approvalExpired()->get();

    assertCollectionsAreSame(collect([$position->id]), $results);
});
