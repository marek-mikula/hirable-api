<?php

namespace App\Http\Resources;

use App\Models\Token;
use Domain\Company\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Token $resource
 */
class TokenInvitationResource extends JsonResource
{
    public function __construct(Token $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'email' => (string) $this->resource->getDataValue('email'),
            'role' => RoleEnum::from((string) $this->resource->getDataValue('role'))->value,
            'isExpired' => $this->resource->is_expired,
            'link' => $this->resource->link,
            'validUntil' => $this->resource->valid_until->toIso8601String(),
            'createdAt' => $this->resource->created_at->toIso8601String(),
        ];
    }
}
