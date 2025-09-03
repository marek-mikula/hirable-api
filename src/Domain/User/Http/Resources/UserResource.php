<?php

declare(strict_types=1);

namespace Domain\User\Http\Resources;

use Domain\User\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property User $resource
 */
class UserResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'fullName' => $this->resource->full_name,
            'label' => $this->resource->label,
            'prefix' => $this->resource->prefix,
            'postfix' => $this->resource->postfix,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
            'role' => $this->resource->company_role,
            'createdAt' => $this->resource->created_at->toIso8601String(),
        ];
    }
}
