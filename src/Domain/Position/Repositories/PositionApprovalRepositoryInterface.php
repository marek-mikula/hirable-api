<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\PositionApproval;

interface PositionApprovalRepositoryInterface
{
    public function store(ModelHasPosition $modelHasPosition): PositionApproval;
}
