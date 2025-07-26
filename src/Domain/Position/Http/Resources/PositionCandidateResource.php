<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Candidate\Http\Resources\CandidateResource;
use Domain\Position\Models\PositionCandidate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property PositionCandidate $resource
 */
class PositionCandidateResource extends JsonResource
{
    use ChecksRelations;

    public function __construct(PositionCandidate $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations(['position', 'candidate', 'step']);

        return [
            'id' => $this->resource->id,
            'score' => $this->resource->score,
            'totalScore' => $this->resource->total_score,
            'positionId' => $this->resource->position->id,
            'positionName' => $this->resource->position->name,
            'candidate' => new CandidateResource($this->resource->candidate),
            'step' => new PositionProcessStepResource($this->resource->step),
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
