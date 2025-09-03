<?php

declare(strict_types=1);

namespace Support\Grid\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\Grid\Data\Definition\GridActionDefinition;

/**
 * @property GridActionDefinition $resource
 */
class GridActionResource extends Resource
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
