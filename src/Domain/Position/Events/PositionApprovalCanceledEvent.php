<?php

declare(strict_types=1);

namespace Domain\Position\Events;

use App\Events\Event;
use Domain\Position\Models\Position;
use Domain\User\Models\User;

class PositionApprovalCanceledEvent extends Event
{
    public function __construct(
        public Position $position,
        public User $canceledBy,
    ) {
    }
}
