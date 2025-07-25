<?php

declare(strict_types=1);

namespace Support\File\Services;

use App\Services\Service;
use Illuminate\Database\Eloquent\Model;
use Support\File\Enums\FileDiskEnum;

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
}
