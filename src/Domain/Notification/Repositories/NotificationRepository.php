<?php

declare(strict_types=1);

namespace Domain\Notification\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Notification\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final readonly class NotificationRepository implements NotificationRepositoryInterface
{
    public function markRead(Notification $notification): Notification
    {
        $notification->read_at = now();

        throw_if(!$notification->save(), RepositoryException::updated(Notification::class));

        return $notification;
    }

    public function getUnreadForModel(Model $model): Collection
    {
        return Notification::query()
            ->whereMorphedTo('notifiable', $model)
            ->unread()
            ->get();
    }

    public function countUnreadForModel(Model $model): int
    {
        return Notification::query()
            ->whereMorphedTo('notifiable', $model)
            ->unread()
            ->count();
    }
}
