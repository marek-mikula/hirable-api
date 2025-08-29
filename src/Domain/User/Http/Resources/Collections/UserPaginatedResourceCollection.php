<?php

declare(strict_types=1);

namespace Domain\User\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedResourceCollection;
use Domain\User\Http\Resources\UserResource;

class UserPaginatedResourceCollection extends PaginatedResourceCollection
{
    public $collects = UserResource::class;
}
