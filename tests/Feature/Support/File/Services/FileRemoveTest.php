<?php

declare(strict_types=1);

namespace Tests\Feature\File\Services;

use Illuminate\Support\Facades\Storage;
use Support\File\Models\File;
use Support\File\Services\FileRemover;

use function Pest\Laravel\assertModelMissing;

/** @covers \Support\File\Services\FileRemover::deleteFiles */
it('it correctly deletes given files from disk and db', function (): void {
    /** @var FileRemover $remover */
    $remover = app(FileRemover::class);

    $file1 = File::factory()->ofDeletedAt(now())->create();
    $file2 = File::factory()->ofDeletedAt(now())->create();

    $storage = Storage::disk($file1->type->getDomain()->getDisk());

    $storage->assertExists($file1->path);
    $storage->assertExists($file2->path);

    $remover->deleteFiles([
        $file1,
        $file2,
    ]);

    $storage->assertMissing($file1->path);
    $storage->assertMissing($file2->path);

    assertModelMissing($file1);
    assertModelMissing($file2);
});
