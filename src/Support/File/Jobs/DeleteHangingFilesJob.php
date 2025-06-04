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
            ->select('files.*')
            ->leftJoin('model_has_files', 'model_has_files.file_id', '=', 'files.id')
            ->whereNull('model_has_files.id')
            ->chunkById(100, function (Collection $files) use ($fileRepository): void {
                $fileRepository->deleteMany($files->all());
            }, 'files.id', 'id');
    }
}
