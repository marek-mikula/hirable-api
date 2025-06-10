<?php

declare(strict_types=1);

namespace Domain\Register\Events;

use App\Events\Event;
use Domain\User\Models\User;
use Support\Token\Models\Token;

class UserRegisteredInvitation extends Event
{
    public function __construct(
        public User $user,
        public Token $token,
    ) {
    }
}
