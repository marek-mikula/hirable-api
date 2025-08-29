<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use App\Http\Resources\Traits\ChecksRelations;
use Domain\Company\Http\Resources\CompanyContactResource;
use Domain\Position\Models\Position;
use Domain\User\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Support\File\Http\Resources\FileResource;

/**
 * @property Position $resource
 */
class PositionShowResource extends PositionResource
{
    use ChecksRelations;

    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations([
            'user',
            'files',
            'hiringManagers',
            'approvers',
            'externalApprovers',
            'approvals',
        ]);

        return array_merge(parent::toArray($request), [
            'files' => new ResourceCollection(FileResource::class, $this->resource->files),
            'hiringManagers' => new ResourceCollection(UserResource::class, $this->resource->hiringManagers),
            'recruiters' => new ResourceCollection(UserResource::class, $this->resource->recruiters),
            'approvers' => new ResourceCollection(UserResource::class, $this->resource->approvers),
            'externalApprovers' => new ResourceCollection(CompanyContactResource::class, $this->resource->externalApprovers),
            'approvals' => new ResourceCollection(PositionApprovalResource::class, $this->resource->approvals),
            'user' => new UserResource($this->resource->user),
        ]);
    }
}
