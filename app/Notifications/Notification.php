<?php

namespace App\Notifications;

use App\Enums\NotificationTypeEnum;
use Illuminate\Notifications\Notification as BaseNotification;

abstract class Notification extends BaseNotification
{
    public NotificationTypeEnum $type;

    public function databaseType($notifiable): string
    {
        // defines the type for database notifications
        return $this->type->value;
    }
}
