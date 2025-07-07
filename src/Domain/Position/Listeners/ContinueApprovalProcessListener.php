<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Events\PositionApprovalApprovedEvent;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;

class ContinueApprovalProcessListener extends Listener
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(PositionApprovalApprovedEvent $event): void
    {
        $hasPendingApprovals = $this->positionApprovalRepository->hasApprovalsOnPositionInState($event->approval->position, PositionApprovalStateEnum::PENDING);

        // there are still some approvals left
        // => ignore this listener
        if ($hasPendingApprovals) {
            return;
        }

        // update position approval process state and round
        $this->positionRepository->updateState($event->approval->position, PositionStateEnum::APPROVAL_APPROVED);
    }
}
