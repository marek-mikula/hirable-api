<?php

declare(strict_types=1);

namespace Support\File\Exceptions;

use Support\File\Data\FileData;
use Support\File\Enums\FileDiskEnum;
use Support\File\Enums\FileTypeEnum;

class UnableToSaveFileException extends \Exception
{
    public function __construct(FileData $file, FileTypeEnum $type, FileDiskEnum $disk, string $path, ?\Exception $previous = null)
    {
        $message = sprintf(
            'Unable to save file on disk %s to %s (type: %s, mime: %s, size: %s).',
            $disk->value,
            $path,
            $type->value,
            $file->getMime(),
            $file->getSize(),
        );

        return parent::__construct(message: $message, previous: $previous);
    }
}
