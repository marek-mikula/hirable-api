<?php

declare(strict_types=1);

namespace Tests\Unit\Support\File\Schedule;

use Illuminate\Support\Facades\Queue;
use Support\File\Jobs\DeleteHangingFilesJob;
use Support\File\Models\File;
use Support\File\Schedule\DeleteHangingFilesSchedule;

/** @covers \Support\File\Schedule\DeleteHangingFilesSchedule::__invoke */
it('dispatches job to delete hanging files', function (): void {
    Queue::fake([
        DeleteHangingFilesJob::class,
    ]);

    DeleteHangingFilesSchedule::call();

    Queue::assertNothingPushed();

    File::factory()->create();

    DeleteHangingFilesSchedule::call();

    Queue::assertPushed(DeleteHangingFilesJob::class);
});
