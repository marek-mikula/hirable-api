<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use Domain\Position\Models\PositionProcessStep;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property PositionProcessStep $resource
 */
class PositionProcessStepResource extends JsonResource
{
    public function __construct(PositionProcessStep $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'step' => is_string($this->resource->step)
                ? $this->resource->step
                : $this->resource->step->value,
            'order' => $this->resource->order,
            'isCustom' => $this->resource->is_custom,
            'isFixed' => $this->resource->is_fixed,
            'isRepeatable' => $this->resource->is_repeatable,
        ];
    }
}
