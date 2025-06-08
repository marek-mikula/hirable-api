<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(User $user, Position $position): void
    {
        DB::transaction(function () use (
            $user,
            $position,
        ): void {
            $this->positionRepository->delete($position);
        }, attempts: 5);
    }
}
