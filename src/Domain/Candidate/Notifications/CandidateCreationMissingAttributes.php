<?php

declare(strict_types=1);

namespace Domain\Candidate\Notifications;

use App\Notifications\QueueNotification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\User\Models\User;
use Support\File\Models\File;

class CandidateCreationMissingAttributes extends QueueNotification
{
    public NotificationTypeEnum $type = NotificationTypeEnum::CANDIDATE_CREATION_MISSING_ATTRIBUTES;

    public function __construct(
        public readonly File $file,
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
            'fileName' => $this->file->name,
        ];
    }
}
