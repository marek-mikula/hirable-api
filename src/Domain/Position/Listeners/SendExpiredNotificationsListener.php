<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Events\PositionApprovalExpiredEvent;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Notifications\PositionApprovalExpiredNotification;
use Domain\User\Models\User;

class SendExpiredNotificationsListener extends QueuedListener
{
    public function handle(PositionApprovalExpiredEvent $event): void
    {
        $owner = $event->position->load('user')->user;

        // send notifications to all approvers
        // and also to the owner of the position
        $event->position
            ->models()
            ->with('model')
            ->whereIn('role', [PositionRoleEnum::APPROVER, PositionRoleEnum::EXTERNAL_APPROVER])
            ->get()
            ->map(fn (ModelHasPosition $modelHasPosition) => $modelHasPosition->model)
            ->add($owner)
            ->each(function (User|CompanyContact $model) use ($event): void {
                $model->notify(new PositionApprovalExpiredNotification(position: $event->position));
            });
    }
}
