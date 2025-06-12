<?php

declare(strict_types=1);

namespace Domain\Password\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Password\Mail\PasswordResetRequestMail;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Token\Models\Token;

class PasswordResetRequestNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::PASSWORD_RESET_REQUEST;

    public function __construct(
        #[WithoutRelations]
        private readonly Token $token
    ) {
        parent::__construct();
    }

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): PasswordResetRequestMail
    {
        return new PasswordResetRequestMail(notifiable: $notifiable, token: $this->token);
    }
}
