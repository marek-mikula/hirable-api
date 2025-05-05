<?php

declare(strict_types=1);

namespace Support\Token\Http\Resources;

use Domain\Company\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Token\Models\Token;

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
            'isUsed' => $this->resource->is_used,
            'link' => $this->resource->link,
            'usedAt' => $this->resource->used_at?->toIso8601String(),
            'validUntil' => $this->resource->valid_until->toIso8601String(),
            'createdAt' => $this->resource->created_at->toIso8601String(),
        ];
    }
}
