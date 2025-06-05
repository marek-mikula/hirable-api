<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Notifications\PositionApprovalReminderNotification;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PositionApprovalRemindUseCase extends UseCase
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository
    ) {
    }

    /**
     * @param Collection<PositionApproval> $approvals
     */
    public function handle(Collection $approvals): void
    {
        DB::transaction(function () use (
            $approvals,
        ): void {
            /** @var PositionApproval $approval */
            foreach ($approvals as $approval) {
                $approval->modelHasPosition->model->notify(new PositionApprovalReminderNotification(
                    position: $approval->position,
                    token: $approval->token,
                ));
            }

            $this->positionApprovalRepository->setRemindedAt($approvals);
        }, attempts: 5);
    }
}
