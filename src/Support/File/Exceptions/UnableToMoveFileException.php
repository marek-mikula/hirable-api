<?php

declare(strict_types=1);

namespace Support\File\Exceptions;

use Support\File\Models\File;

class UnableToMoveFileException extends \Exception
{
    public function __construct(File $file, string $path, ?\Exception $previous = null)
    {
        $message = sprintf(
            'Unable to move file %d from %s to %s (type: %s, mime: %s, size: %s).',
            $file->id,
            $file->path,
            $path,
            $file->type->value,
            $file->mime,
            $file->size,
        );

        return parent::__construct(message: $message, previous: $previous);
    }
}
