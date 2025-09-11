<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Events\PositionCandidateEvaluationFilledEvent;
use Domain\Position\Notifications\PositionCandidateEvaluationFilledNotification;

class SendPositionCandidateEvaluationFilledNotificationsListener extends Listener
{
    public function handle(PositionCandidateEvaluationFilledEvent $event): void
    {
        $event->positionCandidateEvaluation->creator->notify(
            new PositionCandidateEvaluationFilledNotification($event->positionCandidateEvaluation)
        );
    }
}
