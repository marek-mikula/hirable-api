<?php

declare(strict_types=1);

namespace Domain\Company\Notifications;

use App\Notifications\QueueNotification;
use Domain\Company\Mail\InvitationAcceptedMail;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;

class InvitationAcceptedNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::INVITATION_ACCEPTED;

    public function __construct(
        #[WithoutRelations]
        private readonly User $user,
    ) {
        parent::__construct();
    }

    public function via(User $notifiable): array
    {
        return [
            'mail',
            'database',
        ];
    }

    public function toMail(User $notifiable): InvitationAcceptedMail
    {
        return new InvitationAcceptedMail(notifiable: $notifiable, user: $this->user);
    }

    public function toDatabase(User $notifiable): array
    {
        return [
            'userId' => $this->user->id,
            'userName' => $this->user->full_name,
        ];
    }
}
