<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Models\Builders;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\User\Models\User;

use function Tests\Common\Helpers\assertCollectionsAreSame;

/** @covers \Domain\Position\Models\Builders\PositionBuilder::approvalExpired */
it('tests approvalExpired method', function (): void {
    Position::factory()->ofApproveUntil(now())->ofState(PositionStateEnum::APPROVAL_PENDING)->create();
    Position::factory()->ofApproveUntil(now()->subDay())->ofState(PositionStateEnum::APPROVAL_APPROVED)->create();
    $position = Position::factory()->ofApproveUntil(now()->subDay())->ofState(PositionStateEnum::APPROVAL_PENDING)->create();

    $results = Position::query()->approvalExpired()->get();

    assertCollectionsAreSame(collect([$position->id]), $results);
});

/** @covers \Domain\Position\Models\Builders\PositionBuilder::userCanSee */
it('tests userCanSee method', function (): void {
    $user = User::factory()->create();

    $position1 = Position::factory()
        ->ofUser($user)
        ->ofCompany($user->company_id)
        ->create();
    $position2 = Position::factory()
        ->ofUser($user)
        ->ofCompany($user->company_id)
        ->withModel($user, PositionRoleEnum::HIRING_MANAGER)
        ->create();
    $position3 = Position::factory()
        ->ofUser($user)
        ->ofCompany($user->company_id)
        ->withApproval($user, PositionApprovalStateEnum::PENDING)
        ->create();

    Position::factory()
        ->ofCompany($user->company_id)
        ->create();
    Position::factory()
        ->ofCompany($user->company_id)
        ->withApproval($user, PositionApprovalStateEnum::REJECTED)
        ->create();

    $results = Position::query()->userCanSee($user)->get();

    assertCollectionsAreSame(collect([
        $position1,
        $position2,
        $position3,
    ]), $results);
});
