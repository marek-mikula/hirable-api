<?php

namespace Support\File\Exceptions;

use Support\File\Data\FileData;
use Support\File\Enums\FileTypeEnum;

class UnableToSaveFileException extends \Exception
{
    public function __construct(FileData $file, FileTypeEnum $type, ?string $subFolder, ?\Exception $previous = null)
    {
        $message = vsprintf('Unable to save file %s to disk with type %s to %s (mime: %s, size: %s).', [
            $file->getName(),
            $type->value,
            $subFolder ?? '/',
            $file->getMime(),
            $file->getSize(),
        ]);

        return parent::__construct(message: $message, previous: $previous);
    }
}
