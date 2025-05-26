<?php

declare(strict_types=1);

namespace Support\File\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Support\File\Models\File;
use Support\File\Models\ModelHasFile;

final class ModelHasFilesRepository implements ModelHasFilesRepositoryInterface
{
    public function storeMany(Model $fileable, Collection $files): void
    {
        $data = [];

        $now = now();

        /** @var File $file */
        foreach ($files as $file) {
            $data[] = [
                'file_id' => $file->id,
                'fileable_type' => $fileable::class,
                'fileable_id' => $fileable->getKey(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        throw_if(!ModelHasFile::query()->insert($data), RepositoryException::stored(ModelHasFile::class));
    }
}
