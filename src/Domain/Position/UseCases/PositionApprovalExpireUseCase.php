<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionApprovalExpiredEvent;
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
        return DB::transaction(function () use (
            $position
        ): Position {
            $position = $this->positionRepository->updateApproval($position, $position->approval_round, PositionApprovalStateEnum::EXPIRED);

            PositionApprovalExpiredEvent::dispatch($position);

            return $position;
        }, attempts: 5);
    }
}
