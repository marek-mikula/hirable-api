<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PositionApprovalExpireUseCase extends UseCase
{
    public function __construct(
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(Position $position): Position
    {
        return DB::transaction(fn (): Position => $this->positionRepository->updateState($position, PositionStateEnum::APPROVAL_EXPIRED), attempts: 5);
    }
}
