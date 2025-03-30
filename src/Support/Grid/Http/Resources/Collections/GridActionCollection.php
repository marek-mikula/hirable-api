<?php

namespace Support\Grid\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Support\Grid\Http\Resources\GridActionResource;

class GridActionCollection extends ResourceCollection
{
    public $collects = GridActionResource::class;
}
