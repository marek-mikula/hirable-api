<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Mail\PositionExternalApprovalMail;
use Domain\Position\Models\Position;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;

class PositionExternalApprovalNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_EXTERNAL_APPROVAL;

    public function __construct(
        #[WithoutRelations]
        private readonly Position $position,
    ) {
        parent::__construct();
    }

    public function via(CompanyContact $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(CompanyContact $notifiable): PositionExternalApprovalMail
    {
        return new PositionExternalApprovalMail(notifiable: $notifiable, position: $this->position);
    }
}
