<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Resource;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\User\Http\Resources\UserResource;
use Illuminate\Http\Request;

/**
 * @property PositionCandidateEvaluation $resource
 */
class PositionCandidateEvaluationResource extends Resource
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
            'state' => $this->resource->state,
            'evaluation' => $this->resource->evaluation,
            'stars' => $this->resource->stars,
            'fillUntil' => $this->resource->fill_until?->toIso8601String(),
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
            'creator' => new UserResource($this->resource->creator),
            'user' => new UserResource($this->resource->user),
        ];
    }
}
