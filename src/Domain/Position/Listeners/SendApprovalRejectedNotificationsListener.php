<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Events\PositionRejectedEvent;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Notifications\PositionApprovalRejectedNotification;
use Domain\User\Models\User;

class SendApprovalRejectedNotificationsListener extends QueuedListener
{
    public function handle(PositionRejectedEvent $event): void
    {
        $owner = $event->position->user;

        /** @var PositionApproval|null $approval */
        $approval = PositionApproval::query()
            ->with([
                'modelHasPosition',
                'modelHasPosition.model'
            ])
            ->where('position_id', $event->position->id)
            ->where('round', (int) $event->position->approve_round)
            ->where('state', PositionApprovalStateEnum::REJECTED)
            ->first();

        throw_if(!$approval, new \Exception('Approval which rejected the position not found.'));

        $rejectedBy = $approval->modelHasPosition->model;

        // send notifications to all approvers
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
            ->filter(fn (User|CompanyContact $model) => !$model->is($rejectedBy))
            ->each(function (User|CompanyContact $model) use ($event, $approval, $rejectedBy): void {
                $model->notify(
                    new PositionApprovalRejectedNotification(
                        rejectedBy: $rejectedBy,
                        approval: $approval,
                        position: $event->position,
                    )
                );
            });
    }
}
