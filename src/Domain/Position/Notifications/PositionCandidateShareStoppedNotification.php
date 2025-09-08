<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionCandidateShareStoppedNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_CANDIDATE_SHARE_STOPPED;

    public function __construct(
        #[WithoutRelations]
        private readonly PositionCandidate $positionCandidate,
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
            'positionId' => $this->positionCandidate->position->id,
            'positionName' => $this->positionCandidate->position->name,
            'candidateId' => $this->positionCandidate->candidate->id,
            'candidateName' => $this->positionCandidate->candidate->full_name,
        ];
    }
}
