<?php

declare(strict_types=1);

namespace Support\File\Services;

use Illuminate\Http\UploadedFile;
use Support\File\Data\FileData;
use Support\File\Enums\FileTypeEnum;
use Support\File\Exceptions\UnableToCopyFileException;
use Support\File\Exceptions\UnableToSaveFileException;
use Support\File\Models\File;

class FileCopier
{
    public function __construct(
        private readonly FileSaver $fileSaver,
    ) {
    }

    /**
     * @throws UnableToCopyFileException
     */
    public function copyFile(
        File $file,
        string $path,
        FileTypeEnum $type,
    ): File {
        try {
            return $this->fileSaver->saveFile(
                file: FileData::make(new UploadedFile(
                    $file->real_path,
                    $file->name,
                    $file->mime,
                )),
                path: $path,
                type: $type,
                disk: $file->disk,
            );
        } catch (UnableToSaveFileException $unableToSaveFileException) {
            throw new UnableToCopyFileException($file, $path, previous: $unableToSaveFileException);
        }
    }
}
