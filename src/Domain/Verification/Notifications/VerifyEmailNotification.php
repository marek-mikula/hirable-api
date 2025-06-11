<?php

declare(strict_types=1);

namespace Domain\Verification\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\User\Models\User;
use Domain\Verification\Mail\VerifyEmailMail;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Token\Models\Token;

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
