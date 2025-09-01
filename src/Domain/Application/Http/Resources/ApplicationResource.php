<?php

declare(strict_types=1);

namespace Domain\Application\Http\Resources;

use Domain\Application\Models\Application;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property Application $resource
 */
class ApplicationResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->resource->uuid,
        ];
    }
}
