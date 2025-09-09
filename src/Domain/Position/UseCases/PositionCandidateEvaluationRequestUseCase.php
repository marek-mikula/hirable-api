<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\EvaluationStateEnum;
use Domain\Position\Http\Request\Data\PositionCandidateEvaluationRequestData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\Input\PositionCandidateEvaluationStoreInput;
use Domain\Position\Repositories\PositionCandidateEvaluationRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionCandidateEvaluationRequestUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateEvaluationRepositoryInterface $positionCandidateEvaluationRepository,
    ) {
    }

    public function handle(
        User $user,
        Position $position,
        PositionCandidate $positionCandidate,
        PositionCandidateEvaluationRequestData $data
    ): PositionCandidateEvaluation {
        return DB::transaction(function () use (
            $user,
            $position,
            $positionCandidate,
            $data,
        ): PositionCandidateEvaluation {
            return $this->positionCandidateEvaluationRepository->store(
                new PositionCandidateEvaluationStoreInput(
                    creator: $user,
                    positionCandidate: $positionCandidate,
                    user: $data->user,
                    state: EvaluationStateEnum::FILLED,
                    evaluation: null,
                    stars: null,
                    fillUntil: $data->fillUntil,
                )
            );
        }, attempts: 5);
    }
}
