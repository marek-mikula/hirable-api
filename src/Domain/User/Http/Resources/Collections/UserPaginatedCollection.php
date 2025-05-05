<?php

declare(strict_types=1);

namespace Domain\User\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedCollection;
use Domain\User\Http\Resources\UserResource;

class UserPaginatedCollection extends PaginatedCollection
{
    public $collects = UserResource::class;
}
