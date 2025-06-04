<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionApprovalExpiredEvent;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;

class ExpireApprovalProcessListener extends Listener
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
    ) {
    }

    public function handle(PositionApprovalExpiredEvent $event): void
    {
        $position = $event->position;

        $pendingApprovals = $this->positionApprovalRepository->getApprovalsInstate($position, PositionApprovalStateEnum::PENDING);

        // expire all pending approvals
        foreach ($pendingApprovals as $approval) {
            $this->positionApprovalRepository->decide($approval, new PositionApprovalDecideInput(
                state: PositionApprovalStateEnum::EXPIRED,
                note: null,
            ));
        }
    }
}
