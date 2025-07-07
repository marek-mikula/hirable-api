<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Support\Token\Repositories\TokenRepositoryInterface;

class CancelApprovalProcessListener extends Listener
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function handle(PositionApprovalCanceledEvent $event): void
    {
        $pendingApprovals = $this->positionApprovalRepository->getApprovalsOnPositionInstate($event->position, PositionApprovalStateEnum::PENDING, ['token']);

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
    }
}
