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
            'employment_types' => $this->resource->employment_types,
            'employment_forms' => $this->resource->employment_forms,
            'description' => $this->resource->description,
            'is_technical' => $this->resource->is_technical,
            'address' => $this->resource->address,
            'salary_from' => $this->resource->salary_from,
            'salary_to' => $this->resource->salary_to,
            'salary_frequency' => $this->resource->salary_frequency,
            'salary_currency' => $this->resource->salary_currency,
            'salary_var' => $this->resource->salary_var,
            'benefits' => $this->resource->benefits,
            'min_education_level' => $this->resource->min_education_level,
            'seniority' => $this->resource->seniority,
            'experience' => $this->resource->experience,
            'driving_licences' => $this->resource->driving_licences,
            'language_requirements' => $this->resource->language_requirements,
            'required_documents' => $this->resource->required_documents,
            'note' => $this->resource->note,
            'created_at' => $this->resource->created_at->toIso8601String(),
            'updated_at' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
