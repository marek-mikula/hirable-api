<?php

declare(strict_types=1);

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @property LengthAwarePaginator $resource
 */
abstract class PaginatedCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'currentPage' => $this->resource->currentPage(),
                //                'data' => $this->resource->items->toArray(),
                //                'firstPageUrl' => $this->resource->url(1),
                'from' => $this->resource->firstItem(),
                'lastPage' => $this->resource->lastPage(),
                //                'lastPageUrl' => $this->resource->url($this->lastPage()),
                //                'links' => $this->resource->linkCollection()->toArray(),
                //                'nextPageUrl' => $this->resource->nextPageUrl(),
                //                'path' => $this->resource->path(),
                'perPage' => $this->resource->perPage(),
                //                'prevPageUrl' => $this->resource->previousPageUrl(),
                'to' => $this->resource->lastItem(),
                'total' => $this->resource->total(),
            ],
        ];
    }
}
