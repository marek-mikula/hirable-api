<?php

declare(strict_types=1);

namespace Domain\Position\Events;

use App\Events\Event;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;

class PositionCandidateShareDeletedEvent extends Event
{
    public function __construct(
        public readonly User $user,
        public readonly PositionCandidate $positionCandidate,
    ) {
    }
}
