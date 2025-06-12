<?php

declare(strict_types=1);

namespace Domain\Password\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Password\Mail\PasswordChangedMail;
use Domain\User\Models\User;

class PasswordChangedNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::PASSWORD_CHANGED;

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): PasswordChangedMail
    {
        return new PasswordChangedMail(notifiable: $notifiable);
    }
}
