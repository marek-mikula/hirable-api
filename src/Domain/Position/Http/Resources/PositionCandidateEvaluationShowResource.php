<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\User\Http\Resources\UserResource;
use Illuminate\Http\Request;

/**
 * @property PositionCandidateEvaluation $resource
 */
class PositionCandidateEvaluationShowResource extends PositionCandidateEvaluationResource
{
    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations(['creator', 'user']);

        return array_merge(parent::toArray($request), [
            'creator' => new UserResource($this->resource->creator),
            'user' => new UserResource($this->resource->user),
        ]);
    }
}
