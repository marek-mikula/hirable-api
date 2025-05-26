<?php

declare(strict_types=1);

namespace Support\File\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Support\File\Models\File;

interface ModelHasFilesRepositoryInterface
{
    /**
     * @param Model $fileable
     * @param Collection<File> $files
     */
    public function storeMany(Model $fileable, Collection $files): void;
}
