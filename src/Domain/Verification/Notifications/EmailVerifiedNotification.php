<?php

declare(strict_types=1);

namespace Domain\Verification\Notifications;

use App\Notifications\QueueNotification;
use Domain\User\Models\User;
use Domain\Verification\Mail\EmailVerifiedMail;
use Support\Notification\Enums\NotificationTypeEnum;

class EmailVerifiedNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::VERIFICATION_EMAIL_VERIFIED;

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): EmailVerifiedMail
    {
        return new EmailVerifiedMail(notifiable: $notifiable);
    }
}
