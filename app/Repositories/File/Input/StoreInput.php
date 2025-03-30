<?php

namespace App\Repositories\File\Input;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Support\File\Enums\FileTypeEnum;

class StoreInput extends Data
{
    public Model $fileable;

    public FileTypeEnum $type;

    public string $path;

    public string $extension;

    public string $name;

    public string $mime;

    public int $size;

    public array $data;
}
