<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Repositories;

use Domain\Position\Repositories\PositionRepositoryInterface;

/** @covers \Domain\Position\Repositories\PositionRepository::store */
it('tests store method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);
})->todo();
