<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Company\Http\Resources\CompanyContactResource;
use Domain\Position\Models\ModelHasPosition;
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
        $this->checkLoadedRelations(['modelHasPosition'], PositionApproval::class);
        $this->checkLoadedRelations(['model'], ModelHasPosition::class, $this->resource->modelHasPosition);

        return [
            'id' => $this->resource->id,
            'role' => $this->resource->modelHasPosition->role->value,
            'state' => $this->resource->state->value,
            'note' => $this->resource->note,
            'decidedAt' => $this->resource->decided_at?->toIso8601String(),
            'model' => $this->resource->modelHasPosition->is_external
                ? new CompanyContactResource($this->resource->modelHasPosition->model)
                : new UserResource($this->resource->modelHasPosition->model)
        ];
    }
}
