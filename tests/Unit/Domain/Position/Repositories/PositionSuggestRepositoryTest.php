<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Repositories;

use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionSuggestRepositoryInterface;
use Domain\User\Models\User;

use function Tests\Common\Helpers\assertArraysAreSame;

/** @covers \Domain\Position\Repositories\PositionSuggestRepository::suggestDepartments */
it('tests suggestDepartments method', function (): void {
    /** @var PositionSuggestRepositoryInterface $repository */
    $repository = app(PositionSuggestRepositoryInterface::class);

    $user = User::factory()->create();

    $position1 = Position::factory()->ofCompany($user->company_id)->create(['department' => fake()->word]);
    $position2 = Position::factory()->ofCompany($user->company_id)->create(['department' => fake()->word]);
    $position3 = Position::factory()->ofCompany($user->company_id)->create(['department' => fake()->word]);

    assertArraysAreSame([
        $position1->id,
        $position2->id,
        $position3->id,
    ], $repository->suggestDepartments($user, ''));
});
