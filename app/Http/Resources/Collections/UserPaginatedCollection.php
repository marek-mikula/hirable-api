<?php

declare(strict_types=1);

namespace App\Http\Resources\Collections;

use App\Http\Resources\UserResource;

class UserPaginatedCollection extends PaginatedCollection
{
    public $collects = UserResource::class;
}
