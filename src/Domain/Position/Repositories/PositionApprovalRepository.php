<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PositionApprovalRepository implements PositionApprovalRepositoryInterface
{
    public function store(Position $position, ModelHasPosition $modelHasPosition): PositionApproval
    {
        $approval = new PositionApproval();

        $approval->model_has_position_id = $modelHasPosition->id;
        $approval->position_id = $position->id;
        $approval->state = PositionApprovalStateEnum::PENDING;

        throw_if(!$approval->save(), RepositoryException::stored(PositionApproval::class));

        $approval->setRelation('modelHasPosition', $modelHasPosition);
        $approval->setRelation('position', $position);

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

    public function getApprovalsInstate(Position $position, PositionApprovalStateEnum $state): Collection
    {
        return $position
            ->approvals()
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
}
