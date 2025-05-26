<?php

declare(strict_types=1);

namespace Support\File\Repositories\Input;

use Support\File\Enums\FileTypeEnum;

readonly class FileStoreInput
{
    public function __construct(
        public FileTypeEnum $type,
        public string $path,
        public string $extension,
        public string $name,
        public string $mime,
        public int $size,
        public array $data,
    ) {
    }
}
