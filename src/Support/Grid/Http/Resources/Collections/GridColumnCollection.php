<?php

namespace Support\Grid\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Support\Grid\Http\Resources\GridColumnResource;

class GridColumnCollection extends ResourceCollection
{
    public $collects = GridColumnResource::class;
}
