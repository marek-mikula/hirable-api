<?php

namespace App\Repositories\File;

use App\Models\File;
use App\Repositories\File\Input\StoreInput;

interface FileRepositoryInterface
{
    public function store(StoreInput $input): File;

    public function save(File $file): File;

    public function delete(File $file, bool $force = false): File;

    /**
     * @param  File[]  $files
     */
    public function deleteMany(array $files, bool $force = false): void;
}
