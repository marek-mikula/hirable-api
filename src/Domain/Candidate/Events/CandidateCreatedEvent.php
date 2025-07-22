<?php

declare(strict_types=1);

namespace Domain\Candidate\Events;

use App\Events\Event;
use Domain\Candidate\Models\Candidate;

class CandidateCreatedEvent extends Event
{
    public function __construct(
        public Candidate $candidate,
    ) {
    }
}
