<?php

declare(strict_types=1);

namespace Support\File\Repositories;

use Support\File\Models\File;
use Support\File\Repositories\Input\FileStoreInput;

interface FileRepositoryInterface
{
    public function store(FileStoreInput $input): File;

    public function save(File $file): File;

    public function delete(File $file, bool $force = false): File;

    /**
     * @param  File[]  $files
     */
    public function deleteMany(array $files, bool $force = false): void;
}
