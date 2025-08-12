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
use Support\Token\Models\Token;

interface PositionApprovalRepositoryInterface
{
    public function store(Position $position, ModelHasPosition $modelHasPosition, ?Token $token): PositionApproval;

    public function decide(PositionApproval $approval, PositionApprovalDecideInput $input): PositionApproval;

    /**
     * @param string[] $with
     * @return Collection<PositionApproval>
     */
    public function getApprovalsOnPositionInstate(Position $position, PositionApprovalStateEnum $state, array $with): Collection;

    public function hasApprovalsOnPositionInState(Position $position, PositionApprovalStateEnum $state): bool;

    public function hasModelAsApproverOnPositionInState(Position $position, Model $model, PositionApprovalStateEnum $state): bool;

    /**
     * @param string[] $with
     * @return Collection<PositionApproval>
     */
    public function getApprovalsByModelInstate(Model $model, PositionApprovalStateEnum $state, array $with): Collection;

    public function setRemindedAt(PositionApproval $approval, ?Carbon $timestamp): PositionApproval;

    /**
     * @param string[] $with
     */
    public function findByToken(Token $token, array $with): ?PositionApproval;
}
