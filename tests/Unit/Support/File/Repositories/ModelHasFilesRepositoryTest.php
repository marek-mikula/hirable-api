<?php

declare(strict_types=1);

namespace Tests\Unit\Support\File\Repositories;

use Support\File\Repositories\ModelHasFilesRepositoryInterface;

/** @covers \Support\File\Repositories\ModelHasFilesRepository::storeMany */
it('tests storeMany method', function (): void {
    /** @var ModelHasFilesRepositoryInterface $repository */
    $repository = app(ModelHasFilesRepositoryInterface::class);
})->todo();
