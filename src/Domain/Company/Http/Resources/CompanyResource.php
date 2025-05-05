<?php

declare(strict_types=1);

namespace Domain\Company\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Company\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Http\Resources\Collections\ClassifierCollection;

/**
 * @property Company $resource
 */
class CompanyResource extends JsonResource
{
    use ChecksRelations;

    public function __construct(Company $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations(['benefits'], Company::class);

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'idNumber' => $this->resource->id_number,
            'email' => $this->resource->email,
            'website' => $this->resource->website,
            'culture' => $this->resource->culture,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'benefits' => new ClassifierCollection($this->resource->benefits),
        ];
    }
}
