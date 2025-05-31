<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Notifications\PositionApprovalNotification;
use Domain\Position\Notifications\PositionExternalApprovalNotification;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;

class PositionApprovalService
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function sendForApproval(Position $position, ?int $round = null): bool
    {
        if ($position->approval_state !== PositionApprovalStateEnum::PENDING) {
            throw new \Exception('Cannot send position for approval in case it is not in state pending.');
        }

        // normalize the number to number between 1 and 2
        $nextRound = (int) min(2, max(0, ($round ?? 0) + 1));

        // there is no next round
        if ($nextRound === $round) {
            return false;
        }

        $role = match ($nextRound) {
            1 => PositionRoleEnum::HIRING_MANAGER,
            2 => PositionRoleEnum::APPROVER,
        };

        $models = ModelHasPosition::query()
            ->with('model')
            ->where('position_id', $position->id)
            ->where('role', $role->value)
            ->get();

        if ($models->isEmpty()) {
            return $this->sendForApproval($position, $nextRound + 1);
        }

        $this->positionRepository->updateApprovalRound($position, $nextRound);

        $models->each(fn (ModelHasPosition $model) => $this->sendApproval($position, $model));

        return true;
    }

    private function sendApproval(Position $position, ModelHasPosition $model): void
    {
        $this->positionApprovalRepository->store($model);

        $notification = $model->model instanceof CompanyContact
            ? new PositionExternalApprovalNotification($position)
            : new PositionApprovalNotification($position);

        $model->model->notify($notification);
    }
}
