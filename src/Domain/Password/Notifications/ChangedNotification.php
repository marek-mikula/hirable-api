<?php

declare(strict_types=1);

namespace Domain\Password\Notifications;

use App\Notifications\QueueNotification;
use Domain\Password\Mail\ChangedMail;
use Domain\User\Models\User;
use Support\Notification\Enums\NotificationTypeEnum;

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
