<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\User\Models\User;

class PositionProcessStepPolicy
{
    public function store(User $user, Position $position): bool
    {
        if ($position->state !== PositionStateEnum::OPENED) {
            return false;
        }

        /** @see PositionPolicy::update() */
        return $user->can('update', $position);
    }
}
