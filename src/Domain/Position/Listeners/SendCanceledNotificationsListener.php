<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Notifications\PositionApprovalCanceledNotification;
use Domain\User\Models\User;

class SendCanceledNotificationsListener extends QueuedListener
{
    public function handle(PositionApprovalCanceledEvent $event): void
    {
        // send notifications to all approvers
        $event->position
            ->models()
            ->with('model')
            ->whereIn('role', [PositionRoleEnum::APPROVER, PositionRoleEnum::EXTERNAL_APPROVER])
            ->get()
            ->map(fn (ModelHasPosition $modelHasPosition) => $modelHasPosition->model)
            ->each(function (User|CompanyContact $model) use ($event): void {
                $model->notify(
                    new PositionApprovalCanceledNotification(
                        position: $event->position,
                        canceledBy: $event->position->user,
                    )
                );
            });
    }
}
