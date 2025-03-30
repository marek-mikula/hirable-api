<?php

namespace Tests\Unit\Support\File\Jobs;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Support\File\Jobs\DeleteDeletedFilesJob;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

/** @covers \Support\File\Jobs\DeleteDeletedFilesJob::handle */
it('deletes deleted files', function (): void {
    $file = File::factory()->create();
    $deletedFile = File::factory()->ofDeletedAt(now())->create();

    $storage = Storage::disk($deletedFile->type->getDomain()->getDisk());

    $storage->assertExists($deletedFile->path);

    app()->call([new DeleteDeletedFilesJob(), 'handle']);

    assertModelExists($file);
    assertModelMissing($deletedFile);

    $storage->assertMissing($deletedFile->path);
});
