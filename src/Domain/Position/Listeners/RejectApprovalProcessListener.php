<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionRejectedEvent;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;

class RejectApprovalProcessListener extends Listener
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(PositionRejectedEvent $event): void
    {
        $pendingApprovals = $this->positionApprovalRepository->getApprovalsInstate($event->position, PositionApprovalStateEnum::PENDING);

        // cancel all pending approvals
        foreach ($pendingApprovals as $approval) {
            $this->positionApprovalRepository->decide($approval, new PositionApprovalDecideInput(
                state: PositionApprovalStateEnum::CANCELED,
                note: null,
            ));
        }

        // update position approval process state and round
        $this->positionRepository->updateApproval($event->position, round: null, state: PositionApprovalStateEnum::REJECTED);
    }
}
