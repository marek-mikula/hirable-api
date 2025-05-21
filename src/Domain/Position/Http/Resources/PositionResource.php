<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Position\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Position $resource
 */
class PositionResource extends JsonResource
{
    use ChecksRelations;

    public function __construct(Position $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'state' => $this->resource->state,
            'name' => $this->resource->name,
            'department' => $this->resource->department,
            'field' => $this->resource->field,
            'workload' => $this->resource->workload,
            'employmentRelationships' => $this->resource->employment_relationships,
            'employmentForms' => $this->resource->employment_forms,
            'description' => $this->resource->description,
            'isTechnical' => $this->resource->is_technical,
            'address' => $this->resource->address,
            'salaryFrom' => $this->resource->salary_from,
            'salaryTo' => $this->resource->salary_to,
            'salaryFrequency' => $this->resource->salary_frequency,
            'salaryCurrency' => $this->resource->salary_currency,
            'salaryVar' => $this->resource->salary_var,
            'benefits' => $this->resource->benefits,
            'minEducationLevel' => $this->resource->min_education_level,
            'seniority' => $this->resource->seniority,
            'experience' => $this->resource->experience,
            'drivingLicences' => $this->resource->driving_licences,
            'languageRequirements' => $this->resource->language_requirements,
            'requiredDocuments' => $this->resource->required_documents,
            'note' => $this->resource->note,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
