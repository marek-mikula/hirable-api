<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Resources;

use Domain\Notification\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Notification $resource
 */
class NotificationResource extends JsonResource
{
    public function __construct(Notification $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type->value,
            'data' => $this->resource->data,
            'readAt' => $this->resource->read_at?->toIso8601String(),
            'createdAt' => $this->resource->created_at->toIso8601String(),
        ];
    }
}
