<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Mail\PositionOpenedMail;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionOpenedNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_OPENED;

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

    public function toMail(User $notifiable): PositionOpenedMail
    {
        return new PositionOpenedMail(
            notifiable: $notifiable,
            position: $this->position,
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
