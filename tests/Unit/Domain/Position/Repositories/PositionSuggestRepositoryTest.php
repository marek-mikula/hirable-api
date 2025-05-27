<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Repositories;

use Domain\Position\Repositories\PositionSuggestRepositoryInterface;

/** @covers \Domain\Position\Repositories\PositionSuggestRepositoryInterface::suggestDepartments */
it('tests store method', function (): void {
    /** @var PositionSuggestRepositoryInterface $repository */
    $repository = app(PositionSuggestRepositoryInterface::class);
})->todo();
