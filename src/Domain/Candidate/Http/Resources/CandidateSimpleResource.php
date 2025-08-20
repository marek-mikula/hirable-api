<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Resources;

use Domain\Candidate\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Candidate $resource
 */
class CandidateSimpleResource extends JsonResource
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
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'fullName' => $this->resource->full_name,
            'email' => $this->resource->email,
            'phonePrefix' => $this->resource->phone_prefix,
            'phoneNumber' => $this->resource->phone_number,
            'phone' => $this->resource->phone,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
