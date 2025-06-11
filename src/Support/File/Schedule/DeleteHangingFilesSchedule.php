<?php

declare(strict_types=1);

namespace Support\File\Schedule;

use App\Schedule\Schedule;
use Support\File\Jobs\DeleteHangingFilesJob;
use Support\File\Models\File;

/**
 * Deletes files without any connection
 * to any model.
 */
class DeleteHangingFilesSchedule extends Schedule
{
    public function __invoke(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        DeleteHangingFilesJob::dispatch();
    }

    private function shouldRun(): bool
    {
        return File::query()
            ->doesntHave('fileable')
            ->exists();
    }
}
