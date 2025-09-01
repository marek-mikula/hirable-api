<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\Classifier\Data\ClassifierData;
use Support\Classifier\Models\Classifier;

/**
 * @property Classifier|ClassifierData $resource
 */
class ClassifierResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->resource->value,
            'label' => $this->resource->label,
        ];
    }
}
