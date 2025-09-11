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
        /** @var PositionApproval $approval */
        foreach ($approvals as $approval) {
            DB::transaction(function () use ($approval): void {
                $notification = new PositionApprovalReminderNotification(
                    position: $approval->position,
                    token: $approval->token,
                );

                $approval->modelHasPosition->model->notify($notification);

                $this->positionApprovalRepository->setRemindedAt($approval);
            }, attempts: 5);
        }
    }
}
