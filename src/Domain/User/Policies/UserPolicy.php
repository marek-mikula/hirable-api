<?php

declare(strict_types=1);

namespace Domain\User\Policies;

use Domain\User\Models\User;

class UserPolicy
{
    public function update(User $authUser, User $user): bool
    {
        return $authUser->is($user);
    }
}
