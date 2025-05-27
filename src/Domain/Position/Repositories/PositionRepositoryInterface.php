<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;

interface PositionRepositoryInterface
{
    public function store(PositionStoreInput $input): Position;

    public function update(Position $position, PositionUpdateInput $input): Position;
}
