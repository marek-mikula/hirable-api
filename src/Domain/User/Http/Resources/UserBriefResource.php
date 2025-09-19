<?php

declare(strict_types=1);

namespace Domain\User\Http\Resources;

use Domain\User\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property User $resource
 */
class UserBriefResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'fullName' => $this->resource->full_name,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
        ];
    }
}
