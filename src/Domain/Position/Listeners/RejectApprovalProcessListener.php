<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Events\PositionApprovalRejectedEvent;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Support\Token\Repositories\TokenRepositoryInterface;

class RejectApprovalProcessListener extends Listener
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function handle(PositionApprovalRejectedEvent $event): void
    {
        $pendingApprovals = $this->positionApprovalRepository->getApprovalsOnPositionInstate($event->approval->position, PositionApprovalStateEnum::PENDING, ['token']);

        // cancel all pending approvals
        foreach ($pendingApprovals as $approval) {
            $this->positionApprovalRepository->decide($approval, new PositionApprovalDecideInput(
                state: PositionApprovalStateEnum::CANCELED,
                note: null,
            ));

            // remove token for external approvers
            if ($approval->token) {
                $this->tokenRepository->delete($approval->token);
            }
        }

        // update position approval process state,
        $this->positionRepository->updateState($event->approval->position, PositionStateEnum::APPROVAL_REJECTED);
    }
}
