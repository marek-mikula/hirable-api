<?php

declare(strict_types=1);

namespace App\Repositories\File\Input;

use Illuminate\Database\Eloquent\Model;
use Support\File\Enums\FileTypeEnum;

readonly class FileStoreInput
{
    public function __construct(
        public Model $fileable,
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
