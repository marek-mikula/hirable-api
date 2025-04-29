<?php

declare(strict_types=1);

namespace Domain\Password\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\Token;
use App\Models\User;
use App\Notifications\QueueNotification;
use Domain\Password\Mail\ResetRequestMail;
use Illuminate\Queue\Attributes\WithoutRelations;

class ResetRequestNotification extends QueueNotification
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

    public function toMail(User $notifiable): ResetRequestMail
    {
        return new ResetRequestMail(notifiable: $notifiable, token: $this->token);
    }
}
