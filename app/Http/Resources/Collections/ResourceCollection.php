<?php

declare(strict_types=1);

namespace App\Http\Resources\Collections;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

/**
 * @property LengthAwarePaginator $resource
 */
class ResourceCollection extends BaseResourceCollection
{
    /**
     * @param class-string $collects
     */
    public function __construct(string $collects, $resource) // @pest-ignore-type
    {
        parent::__construct($resource);

        $this->collects = $collects;
    }
}
