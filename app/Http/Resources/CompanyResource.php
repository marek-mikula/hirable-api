<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Company $resource
 */
class CompanyResource extends JsonResource
{
    public function __construct(Company $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'idNumber' => $this->resource->id_number,
            'email' => $this->resource->email,
            'website' => $this->resource->website,
            'createdAt' => $this->resource->created_at->toIso8601String(),
        ];
    }
}
