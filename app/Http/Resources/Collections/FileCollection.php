<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\FileResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FileCollection extends ResourceCollection
{
    public $collects = FileResource::class;
}
