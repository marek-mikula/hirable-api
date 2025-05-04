<?php

declare(strict_types=1);

namespace Domain\Register\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Notifications\QueueNotification;
use Domain\Register\Mail\RegisterRequestMail;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Token\Models\Token;

class RegisterRequestNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::REGISTER_REQUEST;

    public function __construct(
        #[WithoutRelations]
        private readonly Token $token,
    ) {
        parent::__construct();
    }

    public function via(AnonymousNotifiable $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(AnonymousNotifiable $notifiable): RegisterRequestMail
    {
        return new RegisterRequestMail(notifiable: $notifiable, token: $this->token);
    }
}
