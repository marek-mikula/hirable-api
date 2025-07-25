<?php

declare(strict_types=1);

namespace Support\File\Repositories\Input;

use Support\File\Enums\FileDiskEnum;
use Support\File\Enums\FileTypeEnum;

readonly class FileStoreInput
{
    public function __construct(
        public FileTypeEnum $type,
        public FileDiskEnum $disk,
        public string $path,
        public string $extension,
        public string $name,
        public string $mime,
        public int $size,
    ) {
    }
}
