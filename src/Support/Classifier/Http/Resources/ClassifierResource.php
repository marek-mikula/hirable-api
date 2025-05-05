<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Models\Classifier;

/**
 * @property Classifier $resource
 */
class ClassifierResource extends JsonResource
{
    public function __construct(Classifier $resource)
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
