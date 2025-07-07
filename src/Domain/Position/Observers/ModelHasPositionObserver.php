<?php

declare(strict_types=1);

namespace Domain\Position\Observers;

use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Notifications\PositionAssignedAsHmNotification;
use Domain\Position\Notifications\PositionAssignedAsRecruiterNotification;
use Domain\Position\Notifications\PositionRemovedAsHmNotification;
use Domain\Position\Notifications\PositionRemovedAsRecruiterNotification;

class ModelHasPositionObserver
{
    public function created(ModelHasPosition $modelHasPosition): void
    {
        $this->sendCreatedNotifications($modelHasPosition);
    }

    public function updated(ModelHasPosition $modelHasPosition): void
    {
        //
    }

    public function deleted(ModelHasPosition $modelHasPosition): void
    {
        $this->sendRemovedNotifications($modelHasPosition);
    }

    public function restored(ModelHasPosition $modelHasPosition): void
    {
        //
    }

    public function forceDeleted(ModelHasPosition $modelHasPosition): void
    {
        //
    }

    private function sendCreatedNotifications(ModelHasPosition $modelHasPosition): void
    {
        $position = $modelHasPosition->position;

        // do not send any notifications
        // when position is not opened
        if ($position->state !== PositionStateEnum::OPENED) {
            return;
        }

        $notification = match ($modelHasPosition->role) {
            PositionRoleEnum::HIRING_MANAGER => new PositionAssignedAsHmNotification($position),
            PositionRoleEnum::RECRUITER => new PositionAssignedAsRecruiterNotification($position),
            default => null,
        };

        if (empty($notification)) {
            return;
        }

        $modelHasPosition->model->notify($notification);
    }

    private function sendRemovedNotifications(ModelHasPosition $modelHasPosition): void
    {
        $position = $modelHasPosition->position;

        // do not send any notifications
        // when position is not opened
        if ($position->state !== PositionStateEnum::OPENED) {
            return;
        }

        $notification = match ($modelHasPosition->role) {
            PositionRoleEnum::HIRING_MANAGER => new PositionRemovedAsHmNotification($position),
            PositionRoleEnum::RECRUITER => new PositionRemovedAsRecruiterNotification($position),
            default => null,
        };

        if (empty($notification)) {
            return;
        }

        $modelHasPosition->model->notify($notification);
    }
}
