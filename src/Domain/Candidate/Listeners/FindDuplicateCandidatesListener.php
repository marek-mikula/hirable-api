<?php

namespace Domain\Candidate\Listeners;

use App\Listeners\QueuedListener;
use Domain\Candidate\Events\CandidateCreatedEvent;

class FindDuplicateCandidatesListener extends QueuedListener
{
    public function handle(CandidateCreatedEvent $event): void
    {
        // todo
    }
}