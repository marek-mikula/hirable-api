<?php

declare(strict_types=1);

namespace Support\File\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Support\File\Models\File;
use Support\File\Models\ModelHasFile;

final class ModelHasFileRepository implements ModelHasFileRepositoryInterface
{
    public function store(Model $fileable, File $file): ModelHasFile
    {
        $modelHasFile = new ModelHasFile();

        $modelHasFile->file_id = $file->id;
        $modelHasFile->fileable_type = $fileable::class;
        $modelHasFile->fileable_id = $fileable->getKey();

        throw_if(!$modelHasFile->save(), RepositoryException::stored(ModelHasFile::class));

        $modelHasFile->setRelation('file', $file);
        $modelHasFile->setRelation('fileable', $fileable);

        return $modelHasFile;
    }

    public function getFileableOf(File $file, string $model): ?Model
    {
        /** @var ModelHasFile|null $modelHasFile */
        $modelHasFile = ModelHasFile::query()
            ->with('fileable')
            ->where('fileable_type', $model)
            ->where('file_id', $file->id)
            ->first();

        return $modelHasFile?->fileable;
    }
}
