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
    public function __construct(ProcessStep $resource)
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
            'isFixed' => $this->resource->is_fixed,
            'isRepeatable' => $this->resource->is_repeatable,
            'isCustom' => $this->resource->is_custom,
        ];
    }
}
