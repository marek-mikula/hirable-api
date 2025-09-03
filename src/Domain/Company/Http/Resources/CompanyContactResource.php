<?php

declare(strict_types=1);

namespace Domain\Company\Http\Resources;

use Domain\Company\Models\CompanyContact;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;

/**
 * @property CompanyContact $resource
 */
class CompanyContactResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'language' => $this->resource->language,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'fullName' => $this->resource->full_name,
            'label' => $this->resource->label,
            'email' => $this->resource->email,
            'note' => $this->resource->note,
            'companyName' => $this->resource->company_name,
        ];
    }
}
