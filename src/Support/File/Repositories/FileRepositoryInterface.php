<?php

declare(strict_types=1);

namespace Support\File\Repositories;

use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Repositories\Input\FileStoreInput;

interface FileRepositoryInterface
{
    public function store(FileStoreInput $input): File;

    public function updatePath(File $file, string $path): File;

    public function updateType(File $file, FileTypeEnum $type): File;

    public function delete(File $file, bool $force): File;

    /**
     * @param  File[]  $files
     */
    public function deleteMany(array $files, bool $force): void;
}
