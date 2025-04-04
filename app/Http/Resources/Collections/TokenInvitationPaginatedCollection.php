<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\TokenInvitationResource;

class TokenInvitationPaginatedCollection extends PaginatedCollection
{
    public $collects = TokenInvitationResource::class;
}
