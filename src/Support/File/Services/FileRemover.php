<?php

namespace Support\File\Services;

use App\Models\File;
use App\Repositories\File\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class FileRemover
{
    public function __construct(private readonly FileRepositoryInterface $fileRepository)
    {
    }

    /**
     * @param  File[]  $files
     */
    public function deleteFiles(array $files): void
    {
        $deletedFiles = collect();

        foreach ($files as $file) {
            $storage = Storage::disk($file->type->getDomain()->getDisk());

            if (!$storage->exists($file->path)) {
                $deletedFiles->push($file);

                continue;
            }

            if ($storage->delete($file->path)) {
                $deletedFiles->push($file);
            }
        }

        throw_if(count($files) !== $deletedFiles->count(), new \Exception('Some files could not have been deleted.'));

        $this->fileRepository->deleteMany($deletedFiles->all(), force: true);
    }
}
