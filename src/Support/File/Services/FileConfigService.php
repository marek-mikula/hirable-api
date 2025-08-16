<?php

declare(strict_types=1);

namespace Support\File\Services;

use App\Services\Service;
use Illuminate\Database\Eloquent\Model;
use Support\File\Enums\FileDiskEnum;
use Support\File\Enums\FileTypeEnum;

class FileConfigService extends Service
{
    public function getDefaultDisk(): FileDiskEnum
    {
        return FileDiskEnum::from((string) config('filesystems.default'));
    }

    public function getModelFolder(Model $model): string
    {
        $folder = config(sprintf('file.model_folders.%s', $model::class));

        throw_if(empty($folder), new \InvalidArgumentException(sprintf('Undefined model folder for model %s.', $model::class)));

        return (string) $folder;
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(FileTypeEnum $type): array
    {
        $extensions = config(sprintf('file.rules.%s.extensions', $type->value), []);

        throw_if(empty($extensions), new \InvalidArgumentException(sprintf('Undefined extensions for type %s.', $type->value)));

        return (array) $extensions;
    }

    public function getFileMaxSize(FileTypeEnum $type): string
    {
        $maxSize = config(sprintf('file.rules.%s.max_size', $type->value));

        throw_if(empty($maxSize), new \InvalidArgumentException(sprintf('Undefined max. size for type %s.', $type->value)));

        return (string) $maxSize;
    }

    public function getFileMaxFiles(FileTypeEnum $type): int
    {
        $maxFiles = config(sprintf('file.rules.%s.max_files', $type->value));

        throw_if(empty($maxFiles), new \InvalidArgumentException(sprintf('Undefined max. files for type %s.', $type->value)));

        return (int) $maxFiles;
    }
}
