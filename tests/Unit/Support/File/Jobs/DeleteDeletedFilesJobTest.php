<?php

declare(strict_types=1);

namespace Tests\Unit\Support\File\Jobs;

use Illuminate\Support\Facades\Storage;
use Support\File\Enums\FileDiskEnum;
use Support\File\Jobs\DeleteDeletedFilesJob;
use Support\File\Models\File;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

/** @covers \Support\File\Jobs\DeleteDeletedFilesJob::handle */
it('correctly deletes deleted files', function (): void {
    $disk = FileDiskEnum::LOCAL;

    $file = File::factory()->ofDisk($disk)->create();
    $deletedFile = File::factory()->ofDisk($disk)->ofDeletedAt(now())->create();

    $storage = Storage::disk($disk->value);

    $storage->assertExists($deletedFile->path);

    app()->call([new DeleteDeletedFilesJob(), 'handle']);

    assertModelExists($file);
    assertModelMissing($deletedFile);

    $storage->assertMissing($deletedFile->path);
});
