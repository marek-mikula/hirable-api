<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Position\Models\Position;
use Domain\User\Http\Resources\UserContactResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\Collections\ClassifierCollection;

/**
 * @property Position $resource
 */
class PositionApplyResource extends JsonResource
{
    use ChecksRelations;

    public function __construct(Position $resource)
    {
        parent::__construct($resource);
    }

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
            'name' => $this->resource->name,
            'workloads' => new ClassifierCollection($toClassifier->handle($this->resource->workloads, ClassifierTypeEnum::WORKLOAD)),
            'employmentRelationships' => new ClassifierCollection($toClassifier->handle($this->resource->employment_relationships, ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP)),
            'employmentForms' => new ClassifierCollection($toClassifier->handle($this->resource->employment_forms, ClassifierTypeEnum::EMPLOYMENT_FORM)),
            'address' => $this->resource->address,
            'salary' => $this->resource->share_salary ? new PositionSalaryResource($this->resource): null,
            'contact' => $this->resource->share_contact ? new UserContactResource($this->resource->user) : null,
            'benefits' => new ClassifierCollection($toClassifier->handle($this->resource->benefits, ClassifierTypeEnum::BENEFIT)),
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
