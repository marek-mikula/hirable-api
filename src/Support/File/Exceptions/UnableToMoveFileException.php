<?php

declare(strict_types=1);

namespace Support\File\Exceptions;

use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;

class UnableToMoveFileException extends \Exception
{
    public function __construct(File $file, FileTypeEnum $type, ?string $subFolder, ?\Exception $previous = null)
    {
        $message = sprintf(
            'Unable to move file %s to disk with type %s from %s to %s (mime: %s, size: %s).',
            $file->name,
            $type->value,
            $file->real_path,
            $subFolder ?? '/',
            $file->mime,
            $file->size,
        );

        return parent::__construct(message: $message, previous: $previous);
    }
}
