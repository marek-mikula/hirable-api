<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class AuthUserResource extends JsonResource
{
    use ChecksRelations;

    public function __construct(User $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations(['company'], User::class);

        return [
            'id' => $this->resource->id,
            'companyRole' => $this->resource->company_role->value,
            'language' => $this->resource->language->value,
            'timezone' => $this->resource->timezone?->value,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'fullName' => $this->resource->full_name,
            'prefix' => $this->resource->prefix,
            'postfix' => $this->resource->postfix,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
            'notifications' => [
                'technical' => [
                    'mail' => $this->resource->notification_technical_mail,
                    'app' => $this->resource->notification_technical_app,
                ],
                'marketing' => [
                    'mail' => $this->resource->notification_marketing_mail,
                    'app' => $this->resource->notification_marketing_app,
                ],
                'application' => [
                    'mail' => $this->resource->notification_application_mail,
                    'app' => $this->resource->notification_application_app,
                ],
            ],
            'company' => new CompanyResource($this->resource->company),
        ];
    }
}
