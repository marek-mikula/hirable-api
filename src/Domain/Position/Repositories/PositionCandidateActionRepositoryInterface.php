<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Enums\ActionStateEnum;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Repositories\Input\PositionCandidateActionStoreInput;
use Domain\Position\Repositories\Input\PositionCandidateActionUpdateInput;

interface PositionCandidateActionRepositoryInterface
{
    public function store(PositionCandidateActionStoreInput $input): PositionCandidateAction;

    public function update(PositionCandidateAction $positionCandidateAction, PositionCandidateActionUpdateInput $input): PositionCandidateAction;

    public function setState(PositionCandidateAction $positionCandidateAction, ActionStateEnum $state): PositionCandidateAction;

    /**
     * @param ActionStateEnum|ActionStateEnum[] $state
     */
    public function existsByTypeAndState(PositionCandidate $positionCandidate, ActionTypeEnum $type, ActionStateEnum|array $state): bool;
}
