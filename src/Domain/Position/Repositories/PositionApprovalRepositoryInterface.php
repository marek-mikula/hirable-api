<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Carbon\Carbon;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PositionApprovalRepositoryInterface
{
    public function store(Position $position, ModelHasPosition $modelHasPosition): PositionApproval;

    public function decide(PositionApproval $approval, PositionApprovalDecideInput $input): PositionApproval;

    /**
     * @return Collection<PositionApproval>
     */
    public function getApprovalsInstate(Position $position, PositionApprovalStateEnum $state): Collection;

    public function hasApprovalsInState(Position $position, PositionApprovalStateEnum $state): bool;

    public function hasModelAsApproverInState(Position $position, Model $model, PositionApprovalStateEnum $state): bool;

    /**
     * @param Collection<PositionApproval> $approvals
     */
    public function setNotifiedAt(Collection $approvals, ?Carbon $timestamp = null): void;
}
