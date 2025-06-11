<?php

declare(strict_types=1);

namespace Domain\Register\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Register\Mail\RegisterRegisteredMail;
use Domain\User\Models\User;

class RegisterRegisteredNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::REGISTER_REGISTERED;

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): RegisterRegisteredMail
    {
        return new RegisterRegisteredMail(notifiable: $notifiable);
    }
}
