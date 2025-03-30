<?php

namespace Support\Grid\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Grid\Data\Definition\GridDefinition;
use Support\Grid\Http\Resources\Collections\GridActionCollection;
use Support\Grid\Http\Resources\Collections\GridColumnCollection;

/**
 * @property GridDefinition $resource
 */
class GridResource extends JsonResource
{
    public function __construct(GridDefinition $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'identifier' => $this->resource->identifier->value,
            'keyAttribute' => $this->resource->keyAttribute,
            'allowSearch' => $this->resource->allowSearch,
            'allowSettings' => $this->resource->allowSettings,
            'allowFilter' => $this->resource->allowFilter,
            'columns' => new GridColumnCollection($this->resource->columns),
            'actions' => new GridActionCollection($this->resource->actions),
            'perPage' => $this->resource->perPage->value,
            'stickyHeader' => $this->resource->stickyHeader,
            'stickyFooter' => $this->resource->stickyFooter,
        ];
    }
}
