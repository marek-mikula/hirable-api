<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Illuminate\Database\Eloquent\Collection;

interface PositionApprovalRepositoryInterface
{
    public function store(Position $position, ModelHasPosition $modelHasPosition): PositionApproval;

    public function decide(PositionApproval $approval, PositionApprovalDecideInput $input): PositionApproval;

    /**
     * @return Collection<PositionApproval>
     */
    public function getApprovalsInstate(Position $position, PositionApprovalStateEnum $state): Collection;

    public function hasApprovalsInState(Position $position, PositionApprovalStateEnum $state): bool;
}
