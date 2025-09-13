<?php

declare(strict_types=1);

namespace Support\File\Services;

use Illuminate\Support\Facades\Storage;
use Support\File\Data\FileData;
use Support\File\Enums\FileDiskEnum;
use Support\File\Enums\FileTypeEnum;
use Support\File\Exceptions\UnableToSaveFileException;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;
use Support\File\Repositories\Input\FileStoreInput;

class FileSaver
{
    public function __construct(
        private readonly FileRepositoryInterface $fileRepository,
        private readonly FileConfigService $fileConfigService,
    ) {
    }

    /**
     * @throws UnableToSaveFileException
     */
    public function saveFile(
        FileData $file,
        string $path,
        FileTypeEnum $type,
        ?FileDiskEnum $disk = null,
    ): File {
        $disk ??= $this->fileConfigService->getDefaultDisk();

        $storage = Storage::disk($disk->value);

        $storedPath = $storage->putFile($path, $file->file);

        if ($storedPath === false) {
            throw new UnableToSaveFileException($file, $type, $disk, $path);
        }

        return $this->fileRepository->store(new FileStoreInput(
            type: $type,
            disk: $disk,
            path: $storedPath,
            extension: $file->getExtension(),
            name: $file->getName(),
            mime: $file->getMime(),
            size: $file->getSize(),
        ));
    }
}
