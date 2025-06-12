<?php

declare(strict_types=1);

namespace Domain\Password\Events;

use App\Events\Event;
use Domain\User\Models\User;

class PasswordChanged extends Event
{
    public function __construct(
        public User $user,
    ) {
    }
}
