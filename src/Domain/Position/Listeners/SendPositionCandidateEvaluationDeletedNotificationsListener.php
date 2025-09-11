<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Events\PositionCandidateEvaluationDeletedEvent;
use Domain\Position\Notifications\PositionCandidateEvaluationCanceledNotification;

class SendPositionCandidateEvaluationDeletedNotificationsListener extends Listener
{
    public function handle(PositionCandidateEvaluationDeletedEvent $event): void
    {
        $event->positionCandidateEvaluation->user->notify(new PositionCandidateEvaluationCanceledNotification(
            creator: $event->positionCandidateEvaluation->creator,
            positionCandidate: $event->positionCandidateEvaluation->positionCandidate,
        ));
    }
}
