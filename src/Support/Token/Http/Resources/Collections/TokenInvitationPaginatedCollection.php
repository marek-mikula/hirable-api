<?php

declare(strict_types=1);

namespace Support\Token\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedCollection;
use Support\Token\Http\Resources\TokenInvitationResource;

class TokenInvitationPaginatedCollection extends PaginatedCollection
{
    public $collects = TokenInvitationResource::class;
}
