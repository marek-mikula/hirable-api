<?php

declare(strict_types=1);

namespace Domain\Application\Http\Resources;

use Domain\Application\TokenProcessing\Data\TokenData;
use Domain\Position\Http\Resources\PositionApplyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property TokenData $resource
 */
class TokenDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'position' => new PositionApplyResource($this->resource->position),
        ];
    }
}
