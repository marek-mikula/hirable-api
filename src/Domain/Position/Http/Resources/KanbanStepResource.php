<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Models\PositionProcessStep;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property PositionProcessStep $resource
 */
class KanbanStepResource extends Resource
{
    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations('positionCandidates');

        return [
            'step' => new PositionProcessStepResource($this->resource),
            'positionCandidates' => new ResourceCollection(PositionCandidateResource::class, $this->resource->positionCandidates),
        ];
    }
}
