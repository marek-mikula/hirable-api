<?php

declare(strict_types=1);

namespace Support\File\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Support\File\Http\Resources\FileResource;

class FileCollection extends ResourceCollection
{
    public $collects = FileResource::class;
}
