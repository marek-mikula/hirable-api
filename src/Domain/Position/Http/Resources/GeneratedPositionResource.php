<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Data\ClassifierData;
use Support\Classifier\Http\Resources\ClassifierResource;

/**
 * @property array $resource
 */
class GeneratedPositionResource extends JsonResource
{
    public function __construct(array $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return array_map([$this, 'mapValue'], $this->resource);
    }

    private function mapValue(mixed $value): mixed
    {
        if ($value instanceof ClassifierData) {
            return new ClassifierResource($value);
        }

        if (is_array($value)) {
            return array_map([$this, 'mapValue'], $value);
        }

        return $value;
    }
}
