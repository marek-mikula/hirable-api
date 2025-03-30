<?php

namespace Domain\Password\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Notifications\QueueNotification;
use Domain\Password\Mail\ChangedMail;

class ChangedNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::PASSWORD_CHANGED;

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): ChangedMail
    {
        return new ChangedMail(notifiable: $notifiable);
    }
}
