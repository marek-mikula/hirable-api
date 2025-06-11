<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Company\Models\CompanyContact;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Mail\PositionApprovalReminderMail;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Token\Models\Token;

class PositionApprovalReminderNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_APPROVAL_REMINDER;

    public function __construct(
        #[WithoutRelations]
        private readonly Position $position,
        #[WithoutRelations]
        private readonly ?Token $token,
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

    public function toMail(User|CompanyContact $notifiable): PositionApprovalReminderMail
    {
        return new PositionApprovalReminderMail(
            notifiable: $notifiable,
            position: $this->position,
            token: $this->token
        );
    }

    public function toDatabase(User $notifiable): array
    {
        return [
            'positionId' => $this->position->id,
            'positionName' => $this->position->name,
        ];
    }
}
