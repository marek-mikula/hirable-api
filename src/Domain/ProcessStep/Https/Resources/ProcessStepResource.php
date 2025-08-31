<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Resources;

use Domain\ProcessStep\Models\ProcessStep;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ProcessStep $resource
 */
class ProcessStepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'step' => $this->resource->step,
            'isRepeatable' => $this->resource->is_repeatable,
            'triggersAction' => $this->resource->triggers_action,
            'isCustom' => $this->resource->is_custom,
        ];
    }
}
