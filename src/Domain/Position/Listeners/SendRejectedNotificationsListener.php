<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Events\PositionApprovalRejectedEvent;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Notifications\PositionApprovalRejectedNotification;
use Domain\User\Models\User;

class SendRejectedNotificationsListener extends QueuedListener
{
    public function handle(PositionApprovalRejectedEvent $event): void
    {
        throw_if($event->position->approval_round === null, new \Exception('Cannot send notifications when approval round is NULL.'));

        $owner = $event->position->load('user')->user;

        // send notifications to all previous and current approvers
        // and also to the owner of the position
        // filter out model who rejected the positions, because he
        // already knows the position is rejected
        $event->position
            ->models()
            ->with('model')
            ->whereIn('role', [PositionRoleEnum::APPROVER, PositionRoleEnum::EXTERNAL_APPROVER])
            ->get()
            ->map(fn (ModelHasPosition $modelHasPosition) => $modelHasPosition->model)
            ->add($owner)
            ->filter(fn (User|CompanyContact $model) => !$model->is($event->rejectedBy))
            ->each(function (User|CompanyContact $model) use ($event): void {
                $model->notify(
                    new PositionApprovalRejectedNotification(
                        rejectedBy: $event->rejectedBy,
                        approval: $event->approval,
                        position: $event->position,
                    )
                );
            });
    }
}
