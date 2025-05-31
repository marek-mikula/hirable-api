<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Notifications\PositionApprovalNotification;
use Domain\Position\Notifications\PositionExternalApprovalNotification;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Repositories\Input\TokenStoreInput;
use Support\Token\Repositories\TokenRepositoryInterface;

class PositionApprovalService
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {
    }

    /**
     * @return Collection<PositionApproval>
     */
    public function sendForApproval(User $user, Position $position, ?int $round = null): Collection
    {
        if ($position->approval_state !== PositionApprovalStateEnum::PENDING) {
            throw new \Exception('Cannot send position for approval in case it is not in state pending.');
        }

        // normalize the number to number between 1 and 2
        $nextRound = (int) min(2, max(0, ($round ?? 0) + 1));

        // there is no next round
        if ($nextRound === $round) {
            return (new PositionApproval())->newCollection();
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
            return $this->sendForApproval($user, $position, $nextRound + 1);
        }

        $this->positionRepository->updateApprovalRound($position, $nextRound);

        return $models->map(fn (ModelHasPosition $model) => $this->sendApproval($user, $position, $model));
    }

    private function sendApproval(User $user, Position $position, ModelHasPosition $model): PositionApproval
    {
        // create approval item
        $approval = $this->positionApprovalRepository->store($position, $model);

        // external approver needs a custom link with token
        if ($model->model instanceof CompanyContact) {
            $token = $this->tokenRepository->store(new TokenStoreInput(
                type: TokenTypeEnum::EXTERNAL_APPROVAL,
                data: ['approvalId' => $approval->id],
                validUntil: now()->addDays(14) // todo make customizable
            ));

            $model->model->notify(new PositionExternalApprovalNotification(
                user: $user,
                position: $position,
                token: $token,
            ));
        } else {
            $model->model->notify(new PositionApprovalNotification(
                user: $user,
                position: $position
            ));
        }

        return $approval;
    }
}
