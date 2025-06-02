<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Mail\PositionRejectedMail;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;

class PositionRejectedNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_REJECTED;

    public function __construct(
        #[WithoutRelations]
        private readonly User|CompanyContact $rejectedBy,
        #[WithoutRelations]
        private readonly PositionApproval $approval,
        #[WithoutRelations]
        private readonly Position $position,
    ) {
        parent::__construct();
    }

    public function via(User|CompanyContact $notifiable): array
    {
        if ($notifiable instanceof CompanyContact) {
            return [
                'mail'
            ];
        }

        return [
            'mail',
            'database',
        ];
    }

    public function toMail(User|CompanyContact $notifiable): PositionRejectedMail
    {
        return new PositionRejectedMail(
            notifiable: $notifiable,
            rejectedBy: $this->rejectedBy,
            approval: $this->approval,
            position: $this->position
        );
    }

    public function toDatabase(User $notifiable): array
    {
        return []; // todo
    }
}
