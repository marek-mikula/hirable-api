<?php

declare(strict_types=1);

namespace Support\Grid\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\Grid\Data\Query\GridQuery;

/**
 * @property GridQuery $resource
 */
class GridQueryResource extends Resource
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
