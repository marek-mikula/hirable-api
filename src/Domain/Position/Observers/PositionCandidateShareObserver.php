<?php

declare(strict_types=1);

namespace Domain\Position\Observers;

use Domain\Position\Events\PositionCandidateShareCreatedEvent;
use Domain\Position\Events\PositionCandidateShareDeletedEvent;
use Domain\Position\Models\PositionCandidateShare;

class PositionCandidateShareObserver
{
    public function created(PositionCandidateShare $positionCandidateShare): void
    {
        PositionCandidateShareCreatedEvent::dispatch($positionCandidateShare);
    }

    public function updated(PositionCandidateShare $positionCandidateShare): void
    {
        //
    }

    public function deleting(PositionCandidateShare $positionCandidateShare): void
    {
        PositionCandidateShareDeletedEvent::dispatch(
            $positionCandidateShare->user,
            $positionCandidateShare->positionCandidate,
        );
    }

    public function deleted(PositionCandidateShare $positionCandidateShare): void
    {
        //
    }

    public function restored(PositionCandidateShare $positionCandidateShare): void
    {
        //
    }

    public function forceDeleted(PositionCandidateShare $positionCandidateShare): void
    {
        //
    }
}
