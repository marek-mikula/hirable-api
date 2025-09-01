<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use Domain\Company\Http\Resources\CompanyContactResource;
use Domain\Position\Models\PositionApproval;
use Domain\User\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property PositionApproval $resource
 */
class PositionApprovalResource extends Resource
{
    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations('modelHasPosition');

        if ($this->resource->modelHasPosition) {
            $this->checkLoadedRelations('model', $this->resource->modelHasPosition);
        }

        if ($this->resource->modelHasPosition && $this->resource->modelHasPosition->is_external) {
            $model = new CompanyContactResource($this->resource->modelHasPosition->model);
        } elseif ($this->resource->modelHasPosition) {
            $model = new UserResource($this->resource->modelHasPosition->model);
        } else {
            $model = null;
        }

        return [
            'id' => $this->resource->id,
            'positionId' => $this->resource->position_id,
            'role' => $this->resource->modelHasPosition?->role,
            'state' => $this->resource->state,
            'round' => $this->resource->round,
            'note' => $this->resource->note,
            'decidedAt' => $this->resource->decided_at?->toIso8601String(),
            'remindedAt' => $this->resource->reminded_at?->toIso8601String(),
            'model' => $model,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
