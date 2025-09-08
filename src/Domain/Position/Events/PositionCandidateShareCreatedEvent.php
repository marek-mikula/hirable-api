<?php

declare(strict_types=1);

namespace Domain\Position\Events;

use App\Events\Event;
use Domain\Position\Models\PositionCandidateShare;

class PositionCandidateShareCreatedEvent extends Event
{
    public function __construct(
        public readonly PositionCandidateShare $positionCandidateShare,
    ) {
    }
}
