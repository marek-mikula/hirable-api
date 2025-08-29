<?php

declare(strict_types=1);

namespace Support\Token\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedResourceCollection;
use Support\Token\Http\Resources\TokenInvitationResource;

class TokenInvitationPaginatedResourceCollection extends PaginatedResourceCollection
{
    public $collects = TokenInvitationResource::class;
}
