<?php

declare(strict_types=1);

namespace Domain\User\Http\Resources;

use Domain\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserContactResource extends JsonResource
{
    public function __construct(User $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'fullName' => $this->resource->full_name,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
        ];
    }
}
