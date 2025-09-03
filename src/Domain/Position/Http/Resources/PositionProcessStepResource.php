<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use Domain\Position\Models\PositionProcessStep;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property PositionProcessStep $resource
 */
class PositionProcessStepResource extends Resource
{
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
