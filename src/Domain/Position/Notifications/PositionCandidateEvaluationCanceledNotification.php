<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionCandidateEvaluationCanceledNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_CANDIDATE_EVALUATION_CANCELED;

    public function __construct(
        #[WithoutRelations]
        private readonly User $creator,
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
            'creatorId' => $this->creator->id,
            'creatorName' => $this->creator->full_name,
            'positionId' => $this->positionCandidate->position->id,
            'positionName' => $this->positionCandidate->position->name,
            'candidateId' => $this->positionCandidate->candidate->id,
            'candidateName' => $this->positionCandidate->candidate->full_name,
            'positionCandidateId' => $this->positionCandidate->id,
        ];
    }
}
