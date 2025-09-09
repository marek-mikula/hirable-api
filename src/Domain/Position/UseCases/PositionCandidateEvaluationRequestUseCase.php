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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PositionCandidateEvaluationRequestUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateEvaluationRepositoryInterface $positionCandidateEvaluationRepository,
    ) {
    }

    /**
     * @return Collection<PositionCandidateEvaluation>
     */
    public function handle(
        User $user,
        Position $position,
        PositionCandidate $positionCandidate,
        PositionCandidateEvaluationRequestData $data
    ): Collection {
        return DB::transaction(function () use (
            $user,
            $position,
            $positionCandidate,
            $data,
        ): Collection {
            $collection = modelCollection(PositionCandidateEvaluation::class);

            /** @var User $hiringManager */
            foreach ($data->hiringManagers as $hiringManager) {
                $collection->push(
                    $this->positionCandidateEvaluationRepository->store(
                        new PositionCandidateEvaluationStoreInput(
                            creator: $user,
                            positionCandidate: $positionCandidate,
                            user: $hiringManager,
                            state: EvaluationStateEnum::WAITING,
                            evaluation: null,
                            stars: null,
                            fillUntil: $data->fillUntil,
                        )
                    )
                );
            }

            return $collection;
        }, attempts: 5);
    }
}
