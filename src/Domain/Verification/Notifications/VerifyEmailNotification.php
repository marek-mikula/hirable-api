<?php

namespace Domain\Verification\Notifications;

use App\Enums\NotificationTypeEnum;
use App\Models\Token;
use App\Models\User;
use App\Notifications\QueueNotification;
use Domain\Verification\Mail\VerifyEmailMail;
use Illuminate\Queue\Attributes\WithoutRelations;

class VerifyEmailNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::VERIFICATION_VERIFY_EMAIL;

    public function __construct(
        #[WithoutRelations]
        private readonly Token $token,
    ) {
        parent::__construct();
    }

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): VerifyEmailMail
    {
        return new VerifyEmailMail(notifiable: $notifiable, token: $this->token);
    }
}
