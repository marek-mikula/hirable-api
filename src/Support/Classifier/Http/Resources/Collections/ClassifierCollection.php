<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedCollection;
use Support\Classifier\Http\Resources\ClassifierResource;

class ClassifierCollection extends PaginatedCollection
{
    public $collects = ClassifierResource::class;
}
