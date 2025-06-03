<?php

declare(strict_types=1);

namespace Support\File\Jobs;

use App\Jobs\ScheduleJob;
use Illuminate\Database\Eloquent\Collection;
use Support\File\Models\File;
use Support\File\Services\FileRemover;

class DeleteDeletedFilesJob extends ScheduleJob
{
    public function handle(FileRemover $fileRemover): void
    {
        File::onlyTrashed()->chunkById(100, function (Collection $files) use ($fileRemover): void {
            $fileRemover->deleteFiles($files->all());
        }, 'files.id', 'id');
    }
}
