<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedCollection;
use Domain\Notification\Http\Resources\NotificationResource;

class NotificationPaginatedCollection extends PaginatedCollection
{
    public $collects = NotificationResource::class;
}
