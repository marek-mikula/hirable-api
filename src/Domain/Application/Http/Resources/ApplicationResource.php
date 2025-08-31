<?php

declare(strict_types=1);

namespace Domain\Application\Http\Resources;

use Domain\Application\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Application $resource
 */
class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->resource->uuid,
        ];
    }
}
