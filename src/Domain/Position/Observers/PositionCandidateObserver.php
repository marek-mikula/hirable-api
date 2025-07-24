<?php

declare(strict_types=1);

namespace Domain\Position\Observers;

use Domain\Position\Events\PositionCandidateCreatedEvent;
use Domain\Position\Models\PositionCandidate;

class PositionCandidateObserver
{
    public function created(PositionCandidate $positionCandidate): void
    {
        PositionCandidateCreatedEvent::dispatch($positionCandidate);
    }

    public function updated(PositionCandidate $positionCandidate): void
    {
        //
    }

    public function deleted(PositionCandidate $positionCandidate): void
    {
        //
    }

    public function restored(PositionCandidate $positionCandidate): void
    {
        //
    }

    public function forceDeleted(PositionCandidate $positionCandidate): void
    {
        //
    }
}
