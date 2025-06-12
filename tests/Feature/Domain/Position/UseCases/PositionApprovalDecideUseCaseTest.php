<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Position\UseCases;

use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Events\PositionApprovalApprovedEvent;
use Domain\Position\Events\PositionApprovalRejectedEvent;
use Domain\Position\Http\Request\Data\PositionApprovalDecideData;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\UseCases\PositionApprovalDecideUseCase;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Event;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \Domain\Position\UseCases\PositionApprovalDecideUseCase::handle */
it('correctly decides position approvals - approve', function (): void {
    Event::fake([
        PositionApprovalApprovedEvent::class,
    ]);

    $user = User::factory()->create();

    $position = Position::factory()
        ->ofState(PositionStateEnum::APPROVAL_PENDING)
        ->ofCompany($user->company_id)
        ->create();

    $model = ModelHasPosition::factory()
        ->ofModel($user)
        ->ofRole(PositionRoleEnum::APPROVER)
        ->create();

    $approval = PositionApproval::factory()
        ->ofModelHasPosition($model)
        ->ofState(PositionApprovalStateEnum::PENDING)
        ->create();

    $data = PositionApprovalDecideData::from([
        'state' => PositionApprovalStateEnum::APPROVED,
        'note' => fake()->text(500)
    ]);

    $approval = PositionApprovalDecideUseCase::make()->handle(
        decidedBy: $user,
        position: $position,
        approval: $approval,
        data: $data
    );

    Event::assertDispatched(PositionApprovalApprovedEvent::class);

    assertSame(PositionApprovalStateEnum::APPROVED, $approval->state);
});

/** @covers \Domain\Position\UseCases\PositionApprovalDecideUseCase::handle */
it('correctly decides position approvals - reject', function (): void {
    Event::fake([
        PositionApprovalRejectedEvent::class,
    ]);

    $user = User::factory()->create();

    $position = Position::factory()
        ->ofState(PositionStateEnum::APPROVAL_PENDING)
        ->ofCompany($user->company_id)
        ->create();

    $model = ModelHasPosition::factory()
        ->ofModel($user)
        ->ofRole(PositionRoleEnum::APPROVER)
        ->create();

    $approval = PositionApproval::factory()
        ->ofModelHasPosition($model)
        ->ofState(PositionApprovalStateEnum::PENDING)
        ->create();

    $data = PositionApprovalDecideData::from([
        'state' => PositionApprovalStateEnum::REJECTED,
        'note' => fake()->text(500)
    ]);

    $approval = PositionApprovalDecideUseCase::make()->handle(
        decidedBy: $user,
        position: $position,
        approval: $approval,
        data: $data
    );

    Event::assertDispatched(PositionApprovalRejectedEvent::class);

    assertSame(PositionApprovalStateEnum::REJECTED, $approval->state);
});

/** @covers \Domain\Position\UseCases\PositionApprovalDecideUseCase::handle */
it('correctly decides position approvals - external approver', function (): void {
    Event::fake([
        PositionApprovalRejectedEvent::class,
    ]);

    $companyContact = CompanyContact::factory()
        ->create();

    $token = Token::factory()
        ->unused()
        ->ofType(TokenTypeEnum::EXTERNAL_APPROVAL)
        ->create();

    $position = Position::factory()
        ->ofCompany($companyContact->company_id)
        ->ofState(PositionStateEnum::APPROVAL_PENDING)
        ->create();

    $model = ModelHasPosition::factory()
        ->ofModel($companyContact)
        ->ofRole(PositionRoleEnum::APPROVER)
        ->create();

    $approval = PositionApproval::factory()
        ->ofModelHasPosition($model)
        ->ofState(PositionApprovalStateEnum::PENDING)
        ->ofToken($token)
        ->create();

    $data = PositionApprovalDecideData::from([
        'state' => PositionApprovalStateEnum::REJECTED,
        'note' => fake()->text(500)
    ]);

    PositionApprovalDecideUseCase::make()->handle(
        decidedBy: $companyContact,
        position: $position,
        approval: $approval,
        data: $data
    );

    $token->refresh();

    assertTrue($token->is_used);
});
