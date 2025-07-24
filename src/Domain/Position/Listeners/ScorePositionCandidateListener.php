<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Events\PositionCandidateCreatedEvent;
use Domain\Position\UseCases\PositionCandidateScoreUseCase;

class ScorePositionCandidateListener extends QueuedListener
{
    public function handle(PositionCandidateCreatedEvent $event): void
    {
        PositionCandidateScoreUseCase::make()->handle($event->positionCandidate);
    }
}
