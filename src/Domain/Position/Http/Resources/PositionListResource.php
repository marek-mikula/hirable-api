<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Models\Position;
use Illuminate\Http\Request;

/**
 * @property Position $resource
 */
class PositionListResource extends PositionResource
{
    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations('approvals');

        return array_merge(parent::toArray($request), [
            'approvals' => new ResourceCollection(PositionApprovalResource::class, $this->resource->approvals),
        ]);
    }
}
