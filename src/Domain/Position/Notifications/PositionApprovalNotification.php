<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Position\Mail\PositionApprovalMail;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;

class PositionApprovalNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_APPROVAL;

    public function __construct(
        #[WithoutRelations]
        private readonly Position $position,
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

    public function toMail(User $notifiable): PositionApprovalMail
    {
        return new PositionApprovalMail(notifiable: $notifiable, position: $this->position);
    }

    public function toDatabase(User $notifiable): array
    {
        return []; // todo
    }
}
