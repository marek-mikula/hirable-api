<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Notifications\PositionApprovalCanceledNotification;
use Domain\Position\Services\PositionApprovalRoundService;
use Domain\User\Models\User;
use Illuminate\Support\Arr;

class SendCanceledNotificationsListener extends QueuedListener
{
    public function __construct(
        private readonly PositionApprovalRoundService $positionApprovalRoundService,
    ) {
    }

    public function handle(PositionApprovalCanceledEvent $event): void
    {
        throw_if($event->position->approval_round === null, new \Exception('Cannot send notifications when approval round is NULL.'));

        $roles = [];

        // collect all roles that needs to be notified
        for ($round = (int) $event->position->approval_round; $round > 0; $round--) {
            $roles = array_merge($roles, $this->positionApprovalRoundService->getRolesByRound($round));
        }

        // send notifications to all previous and current approvers
        $event->position
            ->models()
            ->with('model')
            ->whereIn('role', Arr::pluck($roles, 'value'))
            ->get()
            ->map(fn (ModelHasPosition $modelHasPosition) => $modelHasPosition->model)
            ->each(function (User|CompanyContact $model) use ($event): void {
                $model->notify(
                    new PositionApprovalCanceledNotification(
                        position: $event->position,
                        canceledBy: $event->canceledBy,
                    )
                );
            });
    }
}
