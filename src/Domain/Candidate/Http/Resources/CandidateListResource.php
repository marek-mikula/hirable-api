<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Resources;

use Domain\Candidate\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Candidate $resource
 */
class CandidateListResource extends JsonResource
{
    public function __construct(Candidate $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'companyId' => $this->resource->company_id,
            'language' => $this->resource->language->value,
            'gender' => $this->resource->gender?->value,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'fullName' => $this->resource->full_name,
            'email' => $this->resource->email,
            'phone' => [
                'prefix' => $this->resource->phone_prefix,
                'number' => $this->resource->phone_number,
            ],
            'linkedin' => $this->resource->linkedin,
            'instagram' => $this->resource->instagram,
            'github' => $this->resource->github,
            'portfolio' => $this->resource->portfolio,
            'birthDate' => $this->resource->birth_date?->toIso8601String(),
            'experience' => $this->resource->experience,
            'tags' => $this->resource->tags,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
