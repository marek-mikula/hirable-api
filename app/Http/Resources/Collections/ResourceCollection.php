<?php

declare(strict_types=1);

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;
use Illuminate\Support\Collection;

/**
 * @property Collection $resource
 */
class ResourceCollection extends BaseResourceCollection
{
    /**
     * @param class-string $collects
     */
    public function __construct(string $collects, $resource) // @pest-ignore-type
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }
}
