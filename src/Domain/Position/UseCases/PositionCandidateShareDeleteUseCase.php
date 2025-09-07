<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Repositories\PositionCandidateShareRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionCandidateShareDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateShareRepositoryInterface $positionCandidateShareRepository,
    ) {
    }

    public function handle(User $user, Position $position, PositionCandidate $positionCandidate, PositionCandidateShare $positionCandidateShare): void
    {
        DB::transaction(function () use ($positionCandidateShare): void {
            $this->positionCandidateShareRepository->delete($positionCandidateShare);
        }, attempts: 5);
    }
}
