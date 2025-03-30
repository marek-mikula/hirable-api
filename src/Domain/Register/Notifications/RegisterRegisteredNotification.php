<?php

namespace Domain\Register\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Notifications\QueueNotification;
use Domain\Register\Mail\RegisterRegisteredMail;

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
