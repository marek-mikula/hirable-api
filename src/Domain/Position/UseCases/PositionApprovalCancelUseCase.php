<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionApprovalCancelUseCase extends UseCase
{
    public function __construct(
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(User $user, Position $position): Position
    {
        return DB::transaction(function () use (
            $user,
            $position
        ): Position {
            $position = $this->positionRepository->updateApproval($position, $position->approval_round, PositionApprovalStateEnum::CANCELED);

            PositionApprovalCanceledEvent::dispatch($position, $user);

            return $position;
        }, attempts: 5);
    }
}
