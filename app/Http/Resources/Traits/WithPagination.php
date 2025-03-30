<?php

namespace App\Http\Resources\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @mixin ResourceCollection
 */
trait WithPagination
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof AbstractPaginator || $this->resource instanceof AbstractCursorPaginator) {
            /** @var LengthAwarePaginator $paginator */
            $paginator = $this->resource;

            return [
                'data' => $this->collection->map->toArray($request)->all(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    //                    'data' => $paginator->items->toArray(),
                    //                    'first_page_url' => $paginator->url(1),
                    'from' => $paginator->firstItem(),
                    'last_page' => $paginator->lastPage(),
                    //                    'last_page_url' => $paginator->url($paginator->lastPage()),
                    //                    'links' => $paginator->linkCollection()->toArray(),
                    //                    'next_page_url' => $paginator->nextPageUrl(),
                    //                    'path' => $paginator->path(),
                    'per_page' => $paginator->perPage(),
                    //                    'prev_page_url' => $paginator->previousPageUrl(),
                    'to' => $paginator->lastItem(),
                    'total' => $paginator->total(),
                ],
            ];
        }

        return parent::toArray($request);
    }
}
