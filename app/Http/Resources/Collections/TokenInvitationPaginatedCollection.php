<?php

declare(strict_types=1);

namespace App\Http\Resources\Collections;

use App\Http\Resources\TokenInvitationResource;

class TokenInvitationPaginatedCollection extends PaginatedCollection
{
    public $collects = TokenInvitationResource::class;
}
