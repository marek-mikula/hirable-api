<?php

declare(strict_types=1);

namespace Support\Grid\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Grid\Data\Query\GridQuery;

/**
 * @property GridQuery $resource
 */
class GridQueryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'page' => $this->resource->page,
            'searchQuery' => $this->resource->searchQuery,
            'sort' => $this->resource->sort,
        ];
    }
}
