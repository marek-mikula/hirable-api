<?php

declare(strict_types=1);

namespace Domain\Position\Policies;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\User\Models\User;

class PositionProcessStepPolicy
{
    public function index(User $user, Position $position): bool
    {
        if ($position->state !== PositionStateEnum::OPENED) {
            return false;
        }

        /** @see PositionPolicy::show() */
        return $user->can('show', $position);
    }

    public function store(User $user, Position $position): bool
    {
        if ($position->state !== PositionStateEnum::OPENED) {
            return false;
        }

        /** @see PositionPolicy::update() */
        return $user->can('update', $position);
    }

    public function show(User $user, PositionProcessStep $positionProcessStep, Position $position): bool
    {
        if ($position->state !== PositionStateEnum::OPENED) {
            return false;
        }

        if ($positionProcessStep->position_id !== $position->id) {
            return false;
        }

        /** @see PositionPolicy::show() */
        return $user->can('show', $position);
    }

    public function update(User $user, PositionProcessStep $positionProcessStep, Position $position): bool
    {
        if ($positionProcessStep->position_id !== $position->id) {
            return false;
        }

        if ($position->state !== PositionStateEnum::OPENED) {
            return false;
        }

        /** @see PositionPolicy::update() */
        return $user->can('update', $position);
    }

    public function delete(User $user, PositionProcessStep $positionProcessStep, Position $position): bool
    {
        return $this->update($user, $positionProcessStep, $position);
    }
}
