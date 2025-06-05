<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Company\Http\Resources\CompanyContactResource;
use Domain\Position\Models\PositionApproval;
use Domain\User\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property PositionApproval $resource
 */
class PositionApprovalResource extends JsonResource
{
    use ChecksRelations;

    public function __construct(PositionApproval $resource)
    {
        parent::__construct($resource);
    }

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
            'role' => $this->resource->modelHasPosition?->role->value,
            'state' => $this->resource->state->value,
            'note' => $this->resource->note,
            'decidedAt' => $this->resource->decided_at?->toIso8601String(),
            'remindedAt' => $this->resource->reminded_at?->toIso8601String(),
            'model' => $model,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
