<?php

declare(strict_types=1);

namespace Domain\Application\Notifications;

use App\Notifications\QueueNotification;
use Domain\Application\Mail\ApplicationAcceptedMail;
use Domain\Application\Models\Application;
use Domain\Notification\Enums\NotificationTypeEnum;

class ApplicationAcceptedNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::APPLICATION_ACCEPTED;

    public function via(Application $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(Application $notifiable): ApplicationAcceptedMail
    {
        return new ApplicationAcceptedMail(notifiable: $notifiable);
    }
}
