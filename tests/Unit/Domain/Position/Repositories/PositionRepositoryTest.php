<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Repositories;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionRepositoryInterface;

use function Pest\Laravel\assertModelMissing;
use function PHPUnit\Framework\assertSame;

/** @covers \Domain\Position\Repositories\PositionRepository::store */
it('tests store method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);
})->todo();

/** @covers \Domain\Position\Repositories\PositionRepository::update */
it('tests update method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);
})->todo();

/** @covers \Domain\Position\Repositories\PositionRepository::updateState */
it('tests updateState method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $position = Position::factory()->ofState(PositionStateEnum::DRAFT)->create();

    $position = $repository->updateState($position, PositionStateEnum::OPENED);

    assertSame(PositionStateEnum::OPENED, $position->state);
});

/** @covers \Domain\Position\Repositories\PositionRepository::updateApprovalRound */
it('tests updateApprovalRound method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $position = Position::factory()->create(['approval_round' => null]);

    $newRound = fake()->numberBetween(1, 9);

    $position = $repository->updateApprovalRound($position, $newRound);

    assertSame($newRound, $position->approval_round);
});

/** @covers \Domain\Position\Repositories\PositionRepository::delete */
it('tests delete method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $position = Position::factory()->create();

    $repository->delete($position);

    assertModelMissing($position);
});
