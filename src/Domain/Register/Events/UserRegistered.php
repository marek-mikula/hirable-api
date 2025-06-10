<?php

declare(strict_types=1);

namespace Domain\Register\Events;

use App\Events\Event;
use Domain\User\Models\User;

class UserRegistered extends Event
{
    public function __construct(
        public User $user
    ) {
    }
}
