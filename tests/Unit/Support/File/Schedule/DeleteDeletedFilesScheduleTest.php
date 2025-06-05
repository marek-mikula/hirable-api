<?php

declare(strict_types=1);

namespace Tests\Unit\Support\File\Schedule;

use Illuminate\Support\Facades\Queue;
use Support\File\Jobs\DeleteDeletedFilesJob;
use Support\File\Models\File;
use Support\File\Schedule\DeleteDeletedFilesSchedule;

/** @covers \Support\File\Schedule\DeleteDeletedFilesSchedule */
it('dispatches job to delete deleted files', function (): void {
    Queue::fake([
        DeleteDeletedFilesJob::class,
    ]);

    File::factory()->ofDeletedAt(now())->create();

    DeleteDeletedFilesSchedule::call();

    Queue::assertPushed(DeleteDeletedFilesJob::class);
});
