<?php

declare(strict_types=1);

namespace Domain\Company\Http\Resources;

use Domain\Company\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\Collections\ClassifierCollection;

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
            'environment' => $this->resource->environment,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'benefits' => new ClassifierCollection(ToClassifierAction::make()->handle($this->resource->benefits, ClassifierTypeEnum::BENEFIT)),
        ];
    }
}
