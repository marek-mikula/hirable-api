<?php

declare(strict_types=1);

namespace Support\File\Schedule;

use App\Models\File;
use App\Schedule\Schedule;
use Support\File\Jobs\DeleteDeletedFilesJob;

class DeleteDeletedFilesSchedule extends Schedule
{
    public function __invoke(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        DeleteDeletedFilesJob::dispatch();
    }

    private function shouldRun(): bool
    {
        return File::onlyTrashed()->exists();
    }
}
