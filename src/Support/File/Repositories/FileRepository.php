<?php

declare(strict_types=1);

namespace Support\File\Repositories;

use App\Exceptions\RepositoryException;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Repositories\Input\FileStoreInput;

final class FileRepository implements FileRepositoryInterface
{
    public function store(FileStoreInput $input): File
    {
        $file = new File();

        $file->type = $input->type;
        $file->disk = $input->disk;
        $file->path = $input->path;
        $file->extension = $input->extension;
        $file->name = $input->name;
        $file->mime = $input->mime;
        $file->size = $input->size;

        throw_if(!$file->save(), RepositoryException::stored(File::class));

        return $file;
    }

    public function updatePath(File $file, string $path): File
    {
        $file->path = $path;

        throw_if(!$file->save(), RepositoryException::updated(File::class));

        return $file;
    }

    public function updateType(File $file, FileTypeEnum $type): File
    {
        $file->type = $type;

        throw_if(!$file->save(), RepositoryException::updated(File::class));

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
