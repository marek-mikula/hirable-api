<?php

declare(strict_types=1);

namespace Support\File\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Support\File\Exceptions\UnableToMoveFileException;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;

class FileMover
{
    public function __construct(
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    /**
     * @throws UnableToMoveFileException
     */
    public function moveFile(
        File $file,
        string $path,
    ): File {
        $storage = Storage::disk($file->disk->value);

        $moved = $storage->move($file->path, $newPath = $this->getNewPath($file, $path));

        if ($moved === false) {
            throw new UnableToMoveFileException($file, $newPath);
        }

        return $this->fileRepository->updatePath($file, $newPath);
    }

    private function getNewPath(File $file, string $path): string
    {
        return Str::endsWith($path, '/')
            ? ($path . $file->filename)
            : ($path . DIRECTORY_SEPARATOR . $file->filename);
    }
}
