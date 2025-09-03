<?php

declare(strict_types=1);

namespace Domain\Company\Http\Resources;

use Domain\Company\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property Company $resource
 */
class CompanyResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'idNumber' => $this->resource->id_number,
            'email' => $this->resource->email,
            'website' => $this->resource->website,
            'aiOutputLanguage' => $this->resource->ai_output_language,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
