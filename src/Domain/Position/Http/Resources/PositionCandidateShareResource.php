<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Resource;
use Domain\Position\Models\PositionCandidateShare;
use Domain\User\Http\Resources\UserResource;
use Illuminate\Http\Request;

/**
 * @property PositionCandidateShare $resource
 */
class PositionCandidateShareResource extends Resource
{
    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations([
            'creator',
            'user',
        ]);

        return [
            'id' => $this->resource->id,
            'positionCandidateId' => $this->resource->position_candidate_id,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
            'creator' => new UserResource($this->resource->creator),
            'user' => new UserResource($this->resource->user),
        ];
    }
}
