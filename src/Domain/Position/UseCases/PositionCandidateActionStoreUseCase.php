<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Http\Request\Data\ActionData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionCandidateActionStoreUseCase extends UseCase
{
    public function handle(User $user, Position $position, PositionCandidate $positionCandidate, ActionData $data): PositionCandidateAction
    {



        return DB::transaction(function (): PositionCandidateAction {}, attempts: 5);
    }
}
