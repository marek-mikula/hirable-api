<?php

namespace Support\File\Jobs;

use App\Jobs\ScheduleJob;
use App\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Support\File\Services\FileRemover;

class DeleteDeletedFilesJob extends ScheduleJob
{
    public function handle(FileRemover $fileRemover): void
    {
        File::onlyTrashed()->chunk(100, function (Collection $files) use ($fileRemover): void {
            $fileRemover->deleteFiles($files->all());
        });
    }
}
