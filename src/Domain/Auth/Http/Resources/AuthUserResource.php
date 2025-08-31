<?php

declare(strict_types=1);

namespace Domain\Auth\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class AuthUserResource extends JsonResource
{
    use ChecksRelations;

    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations('company');

        return [
            'id' => $this->resource->id,
            'companyId' => $this->resource->company_id,
            'companyRole' => $this->resource->company_role,
            'companyOwner' => $this->resource->company_owner,
            'companyName' => $this->resource->company->name,
            'language' => $this->resource->language,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'fullName' => $this->resource->full_name,
            'fullQualifiedName' => $this->resource->full_qualified_name,
            'prefix' => $this->resource->prefix,
            'postfix' => $this->resource->postfix,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
        ];
    }
}
