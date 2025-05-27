<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedCollection;
use Domain\Position\Http\Resources\PositionResource;

class PositionPaginatedCollection extends PaginatedCollection
{
    public $collects = PositionResource::class;
}
