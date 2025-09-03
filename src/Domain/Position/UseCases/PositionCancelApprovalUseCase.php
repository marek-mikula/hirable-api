<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionCancelApprovalUseCase extends UseCase
{
    public function __construct(
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(User $user, Position $position): Position
    {
        return DB::transaction(function () use ($position): Position {
            return $this->positionRepository->updateState($position, PositionStateEnum::APPROVAL_CANCELED);
        }, attempts: 5);
    }
}
