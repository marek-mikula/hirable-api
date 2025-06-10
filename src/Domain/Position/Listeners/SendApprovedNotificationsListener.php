<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\QueuedListener;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionApprovalApprovedEvent;
use Domain\Position\Notifications\PositionApprovalApprovedNotification;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;

class SendApprovedNotificationsListener extends QueuedListener
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
    ) {
    }

    public function handle(PositionApprovalApprovedEvent $event): void
    {
        $hasPendingApprovals = $this->positionApprovalRepository->hasApprovalsOnPositionInState($event->position, PositionApprovalStateEnum::PENDING);

        // there are still some approvals left
        // => ignore this listener
        if ($hasPendingApprovals) {
            return;
        }

        $owner = $event->position->load('user')->user;

        // send notification to owner of the position
        $owner->notify(new PositionApprovalApprovedNotification(
            position: $event->position,
        ));
    }
}
