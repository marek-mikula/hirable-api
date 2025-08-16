<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Events\PositionCandidateCreatedEvent;
use Domain\Position\UseCases\PositionCandidateEvaluateUseCase;

class EvaluatePositionCandidateListener extends QueuedListener
{
    public function handle(PositionCandidateCreatedEvent $event): void
    {
        PositionCandidateEvaluateUseCase::make()->handle($event->positionCandidate);
    }
}
