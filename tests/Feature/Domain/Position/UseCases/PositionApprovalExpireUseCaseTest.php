<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Position\UseCases;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Events\PositionApprovalExpiredEvent;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionApprovalExpireUseCase;
use Illuminate\Support\Facades\Event;

use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;

/** @covers \Domain\Position\UseCases\PositionApprovalExpireUseCase::handle */
it('correctly expires position approvals', function (): void {
    Event::fake([
        PositionApprovalExpiredEvent::class,
    ]);

    $position = Position::factory()
        ->ofState(PositionStateEnum::APPROVAL_PENDING)
        ->create();

    assertNotSame(PositionStateEnum::APPROVAL_EXPIRED, $position->state);

    $position = PositionApprovalExpireUseCase::make()->handle($position);

    Event::assertDispatched(PositionApprovalExpiredEvent::class);

    assertSame(PositionStateEnum::APPROVAL_EXPIRED, $position->state);
});
