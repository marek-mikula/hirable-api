<?php

declare(strict_types=1);

namespace Domain\Search\Http\Resources;

use Domain\Search\Data\ResultData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ResultData $resource
 */
class SearchResultResource extends JsonResource
{
    public function __construct(ResultData $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'value' => $this->resource->value,
            'label' => $this->resource->label,
        ];
    }
}
