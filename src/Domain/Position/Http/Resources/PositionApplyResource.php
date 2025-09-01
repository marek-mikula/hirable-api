<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Models\Position;
use Domain\User\Http\Resources\UserContactResource;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\ClassifierResource;

/**
 * @property Position $resource
 */
class PositionApplyResource extends Resource
{
    public function toArray(Request $request): array
    {
        $relationsToCheck = array_filter([
            'company',
            $this->resource->share_contact ? 'user' : null,
        ]);

        $this->checkLoadedRelations($relationsToCheck);

        $toClassifier = ToClassifierAction::make();

        return [
            'companyName' => $this->resource->company->name,
            'companyWebsite' => $this->resource->company->website,
            'name' => $this->resource->extern_name,
            'workloads' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->workloads, ClassifierTypeEnum::WORKLOAD)),
            'employmentRelationships' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->employment_relationships, ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP)),
            'employmentForms' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->employment_forms, ClassifierTypeEnum::EMPLOYMENT_FORM)),
            'address' => $this->resource->address,
            'salary' => $this->resource->share_salary ? new PositionSalaryResource($this->resource) : null,
            'contact' => $this->resource->share_contact ? new UserContactResource($this->resource->user) : null,
            'benefits' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->benefits, ClassifierTypeEnum::BENEFIT)),
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
