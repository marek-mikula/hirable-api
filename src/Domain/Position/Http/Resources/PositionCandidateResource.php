<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Domain\Candidate\Http\Resources\CandidateResource;
use Domain\Position\Models\PositionCandidate;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property PositionCandidate $resource
 */
class PositionCandidateResource extends Resource
{
    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations(['candidate']);

        return [
            'id' => $this->resource->id,
            'positionId' => $this->resource->position_id,
            'score' => $this->resource->score,
            'totalScore' => $this->resource->total_score,
            'isScoreCalculated' => $this->resource->is_score_calculated,
            'idleDays' => $this->resource->idle_days,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
            'candidate' => new CandidateResource($this->resource->candidate),
            'actions' => new ResourceCollection(PositionCandidateActionResource::class, $this->whenLoaded('actions')),
            'latestAction' => new PositionCandidateActionResource($this->whenLoaded('latestAction')),
            'actionsCount' => $this->whenCounted('actions'),
        ];
    }
}
