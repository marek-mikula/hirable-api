<?php

declare(strict_types=1);

namespace Domain\Candidate\Observers;

use Domain\Candidate\Events\CandidateCreatedEvent;
use Domain\Candidate\Models\Candidate;

class CandidateObserver
{
    public function created(Candidate $candidate): void
    {
        CandidateCreatedEvent::dispatch($candidate);
    }

    public function updated(Candidate $candidate): void
    {
        //
    }

    public function deleted(Candidate $candidate): void
    {
        //
    }

    public function restored(Candidate $candidate): void
    {
        //
    }

    public function forceDeleted(Candidate $candidate): void
    {
        //
    }
}
