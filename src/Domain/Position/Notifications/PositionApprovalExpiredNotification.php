<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Company\Models\CompanyContact;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Mail\PositionApprovalExpiredMail;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionApprovalExpiredNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_APPROVAL_EXPIRED;

    public function __construct(
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

    public function toMail(User|CompanyContact $notifiable): PositionApprovalExpiredMail
    {
        return new PositionApprovalExpiredMail(
            notifiable: $notifiable,
            position: $this->position,
        );
    }

    public function toDatabase(User $notifiable): array
    {
        return [
            'positionId' => $this->position->id,
            'positionName' => $this->position->name,
            'ownerId' => $this->position->user_id,
        ];
    }
}
