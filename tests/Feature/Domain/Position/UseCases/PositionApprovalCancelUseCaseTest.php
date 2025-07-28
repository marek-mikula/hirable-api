<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Position\UseCases;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionCancelApprovalUseCase;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Event;

use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;

/** @covers \Domain\Position\UseCases\PositionCancelApprovalUseCase::handle */
it('correctly cancels position approvals', function (): void {
    Event::fake([
        PositionApprovalCanceledEvent::class,
    ]);

    $user = User::factory()->create();
    $position = Position::factory()
        ->ofUser($user)
        ->ofState(PositionStateEnum::APPROVAL_PENDING)
        ->ofCompany($user->company_id)
        ->create();

    assertNotSame(PositionStateEnum::APPROVAL_CANCELED, $position->state);

    $position = PositionCancelApprovalUseCase::make()->handle($user, $position);

    Event::assertDispatched(PositionApprovalCanceledEvent::class);

    assertSame(PositionStateEnum::APPROVAL_CANCELED, $position->state);
});
