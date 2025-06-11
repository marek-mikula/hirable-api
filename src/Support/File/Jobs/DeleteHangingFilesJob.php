<?php

declare(strict_types=1);

namespace Support\File\Jobs;

use App\Jobs\ScheduleJob;
use Illuminate\Database\Eloquent\Collection;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;

class DeleteHangingFilesJob extends ScheduleJob
{
    public function handle(FileRepositoryInterface $fileRepository): void
    {
        File::query()
            ->doesntHave('fileable')
            ->chunkById(100, function (Collection $files) use ($fileRepository): void {
                $fileRepository->deleteMany($files->all());
            }, 'files.id', 'id');
    }
}
