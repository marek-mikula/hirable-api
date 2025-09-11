<?php

declare(strict_types=1);

namespace Domain\Position\Events;

use App\Events\Event;
use Domain\Position\Models\PositionApproval;

class PositionApprovalApprovedEvent extends Event
{
    public function __construct(
        public readonly PositionApproval $approval,
    ) {
    }
}
