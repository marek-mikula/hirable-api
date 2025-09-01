<?php

declare(strict_types=1);

namespace Support\Grid\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\Grid\Data\Definition\GridColumnDefinition;

/**
 * @property GridColumnDefinition $resource
 */
class GridColumnResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->resource->key,
            'label' => $this->resource->label,
            'enabled' => $this->resource->enabled,
            'width' => $this->resource->width,
            'minWidth' => $this->resource->minWidth,
            'maxWidth' => $this->resource->maxWidth,
            'allowToggle' => $this->resource->allowToggle,
            'allowSort' => $this->resource->allowSort,
        ];
    }
}
