<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Notifications\PositionApprovalNotification;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Repositories\Input\TokenStoreInput;
use Support\Token\Repositories\TokenRepositoryInterface;

class PositionApprovalService
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly PositionApprovalRoundService $positionApprovalRoundService,
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

        $nextRound = $this->positionApprovalRoundService->getNextRound($round);

        // there is no next round
        if ($nextRound === $round) {
            return (new PositionApproval())->newCollection();
        }

        $roles = $this->positionApprovalRoundService->getRolesByRound($nextRound);

        $models = ModelHasPosition::query()
            ->with('model')
            ->where('position_id', $position->id)
            ->whereIn('role', Arr::pluck($roles, 'value'))
            ->get();

        if ($models->isEmpty()) {
            return $this->sendForApproval($user, $position, $nextRound + 1);
        }

        $this->positionRepository->updateApproval($position, $nextRound, $position->approval_state);

        return $models->map(fn (ModelHasPosition $model) => $this->sendApproval($user, $position, $model));
    }

    private function sendApproval(User $user, Position $position, ModelHasPosition $model): PositionApproval
    {
        // create approval item
        $approval = $this->positionApprovalRepository->store($position, $model);

        $token = null;

        // external approver needs a custom token
        // for approval process
        if ($model->role === PositionRoleEnum::EXTERNAL_APPROVER) {
            $token = $this->tokenRepository->store(new TokenStoreInput(
                type: TokenTypeEnum::EXTERNAL_APPROVAL,
                data: ['approvalId' => $approval->id],
                validUntil: now()->addDays(14) // todo make customizable
            ));
        }

        $model->model->notify(new PositionApprovalNotification(
            user: $user,
            position: $position,
            token: $token,
        ));

        return $approval;
    }
}
