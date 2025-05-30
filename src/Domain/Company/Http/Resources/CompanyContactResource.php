<?php

declare(strict_types=1);

namespace Domain\Company\Http\Resources;

use Domain\Company\Models\CompanyContact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CompanyContact $resource
 */
class CompanyContactResource extends JsonResource
{
    public function __construct(CompanyContact $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'fullName' => $this->resource->full_name,
            'email' => $this->resource->email,
            'note' => $this->resource->note,
            'companyName' => $this->resource->company_name,
        ];
    }
}
