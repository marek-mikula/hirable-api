<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;

interface PositionRepositoryInterface
{
    public function find(int $positionId, array $with = []): ?Position;

    public function store(PositionStoreInput $input): Position;

    public function update(Position $position, PositionUpdateInput $input): Position;

    public function updateApproval(Position $position, ?int $round, ?PositionApprovalStateEnum $state): Position;
}
