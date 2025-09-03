<?php

declare(strict_types=1);

namespace Domain\Search\Http\Resources;

use Domain\Search\Data\ResultData;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property ResultData $resource
 */
class SearchResultResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->resource->value,
            'label' => $this->resource->label,
        ];
    }
}
