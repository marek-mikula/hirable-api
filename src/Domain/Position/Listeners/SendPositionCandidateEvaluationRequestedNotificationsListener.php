<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Events\PositionCandidateEvaluationRequestedEvent;
use Domain\Position\Notifications\PositionCandidateEvaluationRequestedNotification;

class SendPositionCandidateEvaluationRequestedNotificationsListener extends Listener
{
    public function handle(PositionCandidateEvaluationRequestedEvent $event): void
    {
        $event->positionCandidateEvaluation->user->notify(
            new PositionCandidateEvaluationRequestedNotification($event->positionCandidateEvaluation)
        );
    }
}
