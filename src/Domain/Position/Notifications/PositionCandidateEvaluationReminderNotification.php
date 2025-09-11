<?php

declare(strict_types=1);

namespace Domain\Position\Notifications;

use App\Notifications\QueueNotification;
use Domain\Company\Enums\RoleEnum;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Mail\PositionCandidateEvaluationReminderMail;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\User\Models\User;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionCandidateEvaluationReminderNotification extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::POSITION_CANDIDATE_EVALUATION_REMINDER;

    public function __construct(
        #[WithoutRelations]
        private readonly PositionCandidateEvaluation $positionCandidateEvaluation,
    ) {
        parent::__construct();
    }

    public function via(User $notifiable): array
    {
        if ($notifiable->company_role === RoleEnum::HIRING_MANAGER) {
            return [
                'mail',
                'database',
            ];
        }

        // once the evaluation expires, send email to other
        // roles too, not only to hiring manager
        if ($this->positionCandidateEvaluation->fill_until->isPast()) {
            return [
                'mail',
                'database',
            ];
        }

        return [
            'database',
        ];
    }

    public function toMail(User $notifiable): PositionCandidateEvaluationReminderMail
    {
        return new PositionCandidateEvaluationReminderMail(
            notifiable: $notifiable,
            positionCandidateEvaluation: $this->positionCandidateEvaluation,
        );
    }

    public function toDatabase(User $notifiable): array
    {
        return [
            'creatorId' => $this->positionCandidateEvaluation->creator->id,
            'creatorName' => $this->positionCandidateEvaluation->creator->full_name,
            'positionId' => $this->positionCandidateEvaluation->positionCandidate->position->id,
            'positionName' => $this->positionCandidateEvaluation->positionCandidate->position->name,
            'candidateId' => $this->positionCandidateEvaluation->positionCandidate->candidate->id,
            'candidateName' => $this->positionCandidateEvaluation->positionCandidate->candidate->full_name,
            'positionCandidateId' => $this->positionCandidateEvaluation->positionCandidate->id,
            'fillUntil' => $this->positionCandidateEvaluation->fill_until?->toIso8601String(),
        ];
    }
}
