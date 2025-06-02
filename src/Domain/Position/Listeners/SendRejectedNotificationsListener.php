<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Events\PositionRejectedEvent;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Notifications\PositionRejectedNotification;
use Domain\Position\Services\PositionApprovalRoundService;
use Domain\User\Models\User;
use Illuminate\Support\Arr;

class SendRejectedNotificationsListener extends QueuedListener
{
    public function __construct(
        private readonly PositionApprovalRoundService $positionApprovalRoundService,
    ) {
    }

    public function handle(PositionRejectedEvent $event): void
    {
        if ($event->position->approval_round === null) {
            return;
        }

        $roles = [];

        // collect all roles that needs to be notified
        for ($round = (int) $event->position->approval_round; $round > 0; $round--) {
            $roles = array_merge($roles, $this->positionApprovalRoundService->getRolesByRound($round));
        }

        $owner = $event->position->load('user')->user;

        // send notifications to all previous and current approvers
        // and also to the owner of the position
        $event->position
            ->models()
            ->with('model')
            ->whereIn('role', Arr::pluck($roles, 'value'))
            ->get()
            ->map(fn (ModelHasPosition $modelHasPosition) => $modelHasPosition->model)
            ->add($owner)
            ->each(function (User|CompanyContact $model) use ($event): void {
                $model->notify(
                    new PositionRejectedNotification(
                        rejectedBy: $event->rejectedBy,
                        approval: $event->approval,
                        position: $event->position,
                    )
                );
            });
    }
}
