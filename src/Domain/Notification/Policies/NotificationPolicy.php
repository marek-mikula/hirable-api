<?php

declare(strict_types=1);

namespace Domain\Notification\Policies;

use Domain\Notification\Models\Notification;
use Illuminate\Database\Eloquent\Model;

class NotificationPolicy
{
    public function list(Model $model): bool
    {
        return true;
    }

    public function markAllRead(Model $model): bool
    {
        return true;
    }

    public function markRead(Model $model, Notification $notification): bool
    {
        return $notification->notifiable_type === $model::class &&
            $notification->notifiable_id === $model->getKey() &&
            !$notification->is_read;
    }
}
