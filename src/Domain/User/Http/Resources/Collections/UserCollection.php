<?php

declare(strict_types=1);

namespace Domain\User\Http\Resources\Collections;

use Domain\User\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public $collects = UserResource::class;
}
