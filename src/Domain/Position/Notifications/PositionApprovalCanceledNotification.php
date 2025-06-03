<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Position\Mail\PositionApprovalCanceledMail;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;

class PositionApprovalCanceledNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_APPROVAL_CANCELED;

    public function __construct(
        #[WithoutRelations]
        private readonly Position $position,
        #[WithoutRelations]
        private readonly User $canceledBy,
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

    public function toMail(User $notifiable): PositionApprovalCanceledMail
    {
        return new PositionApprovalCanceledMail(
            notifiable: $notifiable,
            position: $this->position,
            canceledBy: $this->canceledBy,
        );
    }

    public function toDatabase(User $notifiable): array
    {
        return []; // todo
    }
}
