<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionApprovedEvent;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\Position\Services\PositionApprovalRoundService;
use Domain\Position\Services\PositionApprovalService;

class ContinueApprovalProcessListener extends Listener
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly PositionApprovalRoundService $positionApprovalRoundService,
        private readonly PositionApprovalService $positionApprovalService,
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(PositionApprovedEvent $event): void
    {
        $position = $event->position;

        $hasPendingApprovals = $this->positionApprovalRepository->hasApprovalsInState($position, PositionApprovalStateEnum::PENDING);

        // there are still some approvals left
        // => ignore this listener
        if ($hasPendingApprovals) {
            return;
        }

        // position might have a next round of approvers
        if ($this->positionApprovalRoundService->hasNextRound($position->approval_round)) {
            $owner = $event->position->load('user')->user;

            // try to send position to new round of approvals
            $approvals = $this->positionApprovalService->sendForApproval($owner, $event->position, $event->position->approval_round);

            // new approvers were created
            // => stop this listener
            if ($approvals->isNotEmpty()) {
                return;
            }
        }

        // update position approval process state and round
        $this->positionRepository->updateApproval($event->position, round: null, state: PositionApprovalStateEnum::APPROVED);
    }
}
