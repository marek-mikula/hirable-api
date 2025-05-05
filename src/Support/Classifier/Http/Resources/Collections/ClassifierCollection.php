<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Support\Classifier\Http\Resources\ClassifierResource;

class ClassifierCollection extends ResourceCollection
{
    public $collects = ClassifierResource::class;
}
