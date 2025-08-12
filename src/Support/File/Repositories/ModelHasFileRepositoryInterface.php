<?php

declare(strict_types=1);

namespace Support\File\Repositories;

use Illuminate\Database\Eloquent\Model;
use Support\File\Models\File;
use Support\File\Models\ModelHasFile;

interface ModelHasFileRepositoryInterface
{
    public function store(Model $fileable, File $file): ModelHasFile;

    public function getFileableOf(File $file, string $model): ?Model;
}
