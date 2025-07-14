<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Position\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\ClassifierResource;
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
        $this->checkLoadedRelations(['company']);

        $toClassifier = ToClassifierAction::make();

        return [
            'companyName' => $this->resource->company->name,
            'companyWebsite' => $this->resource->company->website,
            'name' => $this->resource->name,
            'workloads' => new ClassifierCollection($toClassifier->handle($this->resource->workloads, ClassifierTypeEnum::WORKLOAD)),
            'employmentRelationships' => new ClassifierCollection($toClassifier->handle($this->resource->employment_relationships, ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP)),
            'employmentForms' => new ClassifierCollection($toClassifier->handle($this->resource->employment_forms, ClassifierTypeEnum::EMPLOYMENT_FORM)),
            'address' => $this->resource->address,
            'salaryFrom' => $this->resource->salary_from,
            'salaryTo' => $this->resource->salary_to,
            'salaryType' => new ClassifierResource($toClassifier->handle($this->resource->salary_type, ClassifierTypeEnum::SALARY_TYPE)),
            'salaryFrequency' => new ClassifierResource($toClassifier->handle($this->resource->salary_frequency, ClassifierTypeEnum::SALARY_FREQUENCY)),
            'salaryCurrency' => new ClassifierResource($toClassifier->handle($this->resource->salary_currency, ClassifierTypeEnum::CURRENCY)),
            'salaryVar' => $this->resource->salary_var,
            'benefits' => new ClassifierCollection($toClassifier->handle($this->resource->benefits, ClassifierTypeEnum::BENEFIT)),
            'languageRequirements' => array_map(function (array $requirement) use ($toClassifier): array {
                $requirement['language'] = new ClassifierResource($toClassifier->handle($requirement['language'], ClassifierTypeEnum::LANGUAGE));
                $requirement['level'] = new ClassifierResource($toClassifier->handle($requirement['level'], ClassifierTypeEnum::LANGUAGE_LEVEL));
                return $requirement;
            }, $this->resource->language_requirements),
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
