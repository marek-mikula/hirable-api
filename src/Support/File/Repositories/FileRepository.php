<?php

declare(strict_types=1);

namespace Support\File\Repositories;

use App\Exceptions\RepositoryException;
use Support\File\Models\File;
use Support\File\Repositories\Input\FileStoreInput;

final class FileRepository implements FileRepositoryInterface
{
    public function store(FileStoreInput $input): File
    {
        $file = new File();

        $file->fileable_type = $input->fileable::class;
        $file->fileable_id = (int) $input->fileable->getAttribute('id');
        $file->type = $input->type;
        $file->path = $input->path;
        $file->extension = $input->extension;
        $file->name = $input->name;
        $file->mime = $input->mime;
        $file->size = $input->size;
        $file->data = $input->data;

        throw_if(!$file->save(), RepositoryException::stored(File::class));

        $file->setRelation('fileable', $input->fileable);

        return $file;
    }

    public function save(File $file): File
    {
        throw_if(!$file->save(), RepositoryException::saved(File::class));

        return $file;
    }

    public function delete(File $file, bool $force = false): File
    {
        if ($force) {
            throw_if(!$file->forceDelete(), RepositoryException::forceDeleted(File::class));
        } else {
            throw_if(!$file->delete(), RepositoryException::deleted(File::class));
        }

        return $file;
    }

    public function deleteMany(array $files, bool $force = false): void
    {
        $ids = collect($files)->pluck('id');

        $query = $force
            ? File::onlyTrashed()->whereIn('id', $ids)
            : File::query()->whereIn('id', $ids);

        if ($force) {
            throw_if($query->forceDelete() !== $ids->count(), RepositoryException::forceDeleted(File::class));
        } else {
            throw_if($query->delete() !== $ids->count(), RepositoryException::deleted(File::class));
        }
    }
}
