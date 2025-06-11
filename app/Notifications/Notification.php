<?php

declare(strict_types=1);

namespace App\Notifications;

use Domain\Notification\Enums\NotificationTypeEnum;
use Illuminate\Notifications\Notification as BaseNotification;

abstract class Notification extends BaseNotification
{
    public NotificationTypeEnum $type;

    public function databaseType($notifiable): string // @pest-ignore-type
    {
        // defines the type for database notifications
        return $this->type->value;
    }
}
