<?php

declare(strict_types=1);

namespace Domain\Application\Events;

use App\Events\Event;
use Domain\Application\Models\Application;

class ApplicationCreatedEvent extends Event
{
    public function __construct(
        public Application $application,
    ) {
    }
}
