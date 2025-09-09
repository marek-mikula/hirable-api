<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Resource;
use Domain\Position\Models\PositionCandidateEvaluation;
use Illuminate\Http\Request;

/**
 * @property PositionCandidateEvaluation $resource
 */
class PositionCandidateEvaluationResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'creatorId' => $this->resource->creator_id,
            'positionCandidateId' => $this->resource->position_candidate_id,
            'userId' => $this->resource->user_id,
            'state' => $this->resource->state,
            'evaluation' => $this->resource->evaluation,
            'stars' => $this->resource->stars,
            'fillUntil' => $this->resource->fill_until?->toIso8601String(),
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
