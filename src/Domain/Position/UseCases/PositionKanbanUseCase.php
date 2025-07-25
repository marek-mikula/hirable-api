<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;

class PositionKanbanUseCase extends UseCase
{
    public function handle(User $user, Position $position): array
    {
        $items = PositionCandidate::query()
            ->with(['position', 'candidate'])
            ->get();
    }
}
