<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;

interface PositionRepositoryInterface
{
    public function store(PositionStoreInput $input): Position;

    public function update(Position $position, PositionUpdateInput $input): Position;

    public function updateState(Position $position, PositionStateEnum $state): Position;

    public function delete(Position $position): void;

    public function updateApproveRound(Position $position, int $round): Position;

    public function setTokens(Position $position, string $commonToken, string $internToken, string $referralToken): Position;
}
