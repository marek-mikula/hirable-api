<?php

declare(strict_types=1);

namespace Domain\Position\Events;

use App\Events\Event;
use Domain\Position\Models\Position;

class PositionOpenedEvent extends Event
{
    public function __construct(
        public Position $position,
    ) {
    }
}
