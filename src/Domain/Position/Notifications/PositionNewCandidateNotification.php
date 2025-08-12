<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Candidate\Models\Candidate;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionNewCandidateNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_NEW_CANDIDATE;

    public function __construct(
        #[WithoutRelations]
        private readonly Position $position,
        #[WithoutRelations]
        private readonly Candidate $candidate,
    ) {
        parent::__construct();
    }

    public function via(User $notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toDatabase(User $notifiable): array
    {
        return [
            'positionId' => $this->position->id,
            'positionName' => $this->position->name,
            'candidateId' => $this->candidate->id,
            'candidateName' => $this->candidate->full_name,
        ];
    }
}
