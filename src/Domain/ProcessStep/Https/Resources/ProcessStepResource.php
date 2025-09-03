<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Resources;

use Domain\ProcessStep\Models\ProcessStep;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property ProcessStep $resource
 */
class ProcessStepResource extends Resource
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
