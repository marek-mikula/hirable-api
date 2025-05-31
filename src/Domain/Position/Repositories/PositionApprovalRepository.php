<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\PositionApproval;

class PositionApprovalRepository implements PositionApprovalRepositoryInterface
{
    public function store(ModelHasPosition $modelHasPosition): PositionApproval
    {
        $positionApproval = new PositionApproval();

        $positionApproval->model_has_position_id = $modelHasPosition->id;
        $positionApproval->state = PositionApprovalStateEnum::PENDING;

        throw_if(!$positionApproval->save(), RepositoryException::stored(PositionApproval::class));

        return $positionApproval;
    }
}
