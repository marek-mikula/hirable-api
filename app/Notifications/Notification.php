<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Notification as BaseNotification;
use Support\Notification\Enums\NotificationTypeEnum;

abstract class Notification extends BaseNotification
{
    public NotificationTypeEnum $type;

    public function databaseType($notifiable): string
    {
        // defines the type for database notifications
        return $this->type->value;
    }
}
