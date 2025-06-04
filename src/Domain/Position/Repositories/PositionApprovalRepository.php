<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Support\Token\Models\Token;

class PositionApprovalRepository implements PositionApprovalRepositoryInterface
{
    public function store(Position $position, ModelHasPosition $modelHasPosition, ?Token $token): PositionApproval
    {
        $approval = new PositionApproval();

        $approval->model_has_position_id = $modelHasPosition->id;
        $approval->position_id = $position->id;
        $approval->token_id = $token?->id;

        $approval->state = PositionApprovalStateEnum::PENDING;

        throw_if(!$approval->save(), RepositoryException::stored(PositionApproval::class));

        $approval->setRelation('modelHasPosition', $modelHasPosition);
        $approval->setRelation('position', $position);

        if ($token) {
            $approval->setRelation('token', $token);
        }

        return $approval;
    }

    public function decide(PositionApproval $approval, PositionApprovalDecideInput $input): PositionApproval
    {
        $approval->state = $input->state;
        $approval->note = $input->note;
        $approval->decided_at = now();

        throw_if(!$approval->save(), RepositoryException::updated(PositionApproval::class));

        return $approval;
    }

    public function getApprovalsInstate(Position $position, PositionApprovalStateEnum $state, array $with = []): Collection
    {
        return $position
            ->approvals()
            ->with($with)
            ->where('state', $state->value)
            ->get();
    }

    public function hasApprovalsInState(Position $position, PositionApprovalStateEnum $state): bool
    {
        return $position
            ->approvals()
            ->where('state', $state->value)
            ->exists();
    }

    public function hasModelAsApproverInState(Position $position, Model $model, PositionApprovalStateEnum $state): bool
    {
        return $position
            ->approvals()
            ->whereHas('modelHasPosition', function (Builder $query) use ($model): void {
                $query
                    ->where('model_has_positions.model_type', $model::class)
                    ->where('model_has_positions.model_id', $model->getKey());
            })
            ->where('state', $state->value)
            ->exists();
    }

    public function setNotifiedAt(Collection $approvals, ?Carbon $timestamp = null): void
    {
        $result = PositionApproval::query()
            ->whereIn('id', $approvals->pluck('id'))
            ->update([
                'notified_at' => $timestamp ?? now(),
            ]);

        throw_if($result !== $approvals->count(), RepositoryException::updated(PositionApproval::class));
    }

    public function findByToken(Token $token, array $with = []): ?PositionApproval
    {
        return PositionApproval::query()
            ->with($with)
            ->where('token_id', $token->id)
            ->first();
    }
}
