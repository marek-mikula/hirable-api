<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use App\Services\Service;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Notifications\PositionApprovalNotification;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Repositories\Input\TokenStoreInput;
use Support\Token\Repositories\TokenRepositoryInterface;

class PositionApprovalService extends Service
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
    public function sendForApproval(User $user, Position $position): Collection
    {
        if ($position->state !== PositionStateEnum::APPROVAL_PENDING) {
            throw new \Exception('Cannot send position for approval in case it is not in state pending.');
        }

        $models = ModelHasPosition::query()
            ->with('model')
            ->where('position_id', $position->id)
            ->whereIn('role', [PositionRoleEnum::APPROVER, PositionRoleEnum::EXTERNAL_APPROVER])
            ->get();

        if ($models->isEmpty()) {
            throw new \Exception('Missing approvers when sending for approval.');
        }

        $position = $this->positionRepository->updateApproveRound($position, round: ($position->approve_round ?? 0) + 1);

        return $models->map(fn (ModelHasPosition $model) => $this->sendApproval($user, $position, $model));
    }

    private function sendApproval(User $user, Position $position, ModelHasPosition $model): PositionApproval
    {
        $token = null;

        // external approver needs a custom token
        // for approval process
        if ($model->is_external) {
            $token = $this->tokenRepository->store(new TokenStoreInput(
                type: TokenTypeEnum::EXTERNAL_APPROVAL,
                validUntil: $position->approve_until->copy()->endOfDay(),
            ));
        }

        // create approval item
        $approval = $this->positionApprovalRepository->store($position, $model, $token);

        $model->model->notify(new PositionApprovalNotification(
            user: $user,
            position: $position,
            token: $token,
        ));

        return $approval;
    }
}
