<?php

declare(strict_types=1);

namespace Domain\Notification\Repositories;

use Domain\Notification\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface NotificationRepositoryInterface
{
    public function markRead(Notification $notification): Notification;

    /**
     * @return Collection<Notification>
     */
    public function getUnreadForModel(Model $model): Collection;

    public function countUnreadForModel(Model $model): int;
}
