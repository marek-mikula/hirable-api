<?php

declare(strict_types=1);

namespace Domain\Company\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\Token;
use App\Notifications\QueueNotification;
use Domain\Company\Mail\InvitationSentMail;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Queue\Attributes\WithoutRelations;

class InvitationSentNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::INVITATION_SENT;

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

    public function toMail(AnonymousNotifiable $notifiable): InvitationSentMail
    {
        return new InvitationSentMail(notifiable: $notifiable, token: $this->token);
    }
}
