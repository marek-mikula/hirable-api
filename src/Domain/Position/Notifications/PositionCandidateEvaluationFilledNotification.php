<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionCandidateEvaluationFilledNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_CANDIDATE_EVALUATION_FILLED;

    public function __construct(
        #[WithoutRelations]
        private readonly PositionCandidateEvaluation $positionCandidateEvaluation,
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
            'userId' => $this->positionCandidateEvaluation->user->id,
            'userName' => $this->positionCandidateEvaluation->user->full_name,
            'positionId' => $this->positionCandidateEvaluation->positionCandidate->position->id,
            'positionName' => $this->positionCandidateEvaluation->positionCandidate->position->name,
            'candidateId' => $this->positionCandidateEvaluation->positionCandidate->candidate->id,
            'candidateName' => $this->positionCandidateEvaluation->positionCandidate->candidate->full_name,
            'positionCandidateId' => $this->positionCandidateEvaluation->positionCandidate->id,
        ];
    }
}
