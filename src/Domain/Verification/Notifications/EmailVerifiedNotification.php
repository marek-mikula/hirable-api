<?php

namespace Domain\Verification\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Notifications\QueueNotification;
use Domain\Verification\Mail\EmailVerifiedMail;

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
