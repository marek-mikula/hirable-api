<?php

declare(strict_types=1);

namespace Domain\Candidate\Notifications;

use App\Notifications\QueueNotification;
use Domain\Candidate\Models\Candidate;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\User\Models\User;

class CandidateCreationSucceeded extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::CANDIDATE_CREATION_SUCCEEDED;

    public function __construct(
        public readonly Candidate $candidate,
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
            'candidateId' => $this->candidate->id,
            'candidateName' => $this->candidate->full_name,
        ];
    }
}
