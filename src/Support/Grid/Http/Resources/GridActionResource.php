<?php

declare(strict_types=1);

namespace Support\Grid\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Grid\Data\Definition\GridActionDefinition;

/**
 * @property GridActionDefinition $resource
 */
class GridActionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->resource->key,
            'label' => $this->resource->label,
            'needsRefresh' => $this->resource->needsRefresh,
        ];
    }
}
