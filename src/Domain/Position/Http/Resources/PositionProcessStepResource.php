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
            'step' => $this->resource->step,
            'label' => $this->resource->label,
            'order' => $this->resource->order,
            'isCustom' => $this->resource->is_custom,
            'isFixed' => $this->resource->is_fixed,
            'isRepeatable' => $this->resource->is_repeatable,
            'triggersAction' => $this->resource->triggers_action,
        ];
    }
}
