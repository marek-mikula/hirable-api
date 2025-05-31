<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Mail\PositionExternalApprovalMail;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;
use Support\Token\Models\Token;

class PositionExternalApprovalNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_EXTERNAL_APPROVAL;

    public function __construct(
        #[WithoutRelations]
        private readonly User $user,
        #[WithoutRelations]
        private readonly Position $position,
        #[WithoutRelations]
        private readonly Token $token,
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
        return new PositionExternalApprovalMail(
            notifiable: $notifiable,
            user: $this->user,
            position: $this->position,
            token: $this->token,
        );
    }
}
