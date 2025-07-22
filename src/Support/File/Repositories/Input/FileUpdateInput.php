<?php

declare(strict_types=1);

namespace Support\File\Repositories\Input;

use Illuminate\Database\Eloquent\Model;
use Support\File\Enums\FileTypeEnum;

readonly class FileUpdateInput
{
    public function __construct(
        public Model $model,
        public FileTypeEnum $type,
        public string $path,
    ) {
    }
}
