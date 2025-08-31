<?php

declare(strict_types=1);

namespace Support\Grid\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Grid\Data\Definition\GridDefinition;

/**
 * @property GridDefinition $resource
 */
class GridResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'identifier' => $this->resource->identifier->value,
            'keyAttribute' => $this->resource->keyAttribute,
            'allowSearch' => $this->resource->allowSearch,
            'allowSettings' => $this->resource->allowSettings,
            'allowFilter' => $this->resource->allowFilter,
            'columns' => new ResourceCollection(GridColumnResource::class, $this->resource->columns),
            'actions' => new ResourceCollection(GridActionResource::class, $this->resource->actions),
            'perPage' => $this->resource->perPage->value,
            'stickyHeader' => $this->resource->stickyHeader,
            'stickyFooter' => $this->resource->stickyFooter,
        ];
    }
}
