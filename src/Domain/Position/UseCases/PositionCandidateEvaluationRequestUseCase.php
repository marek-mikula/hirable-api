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
        User $creator,
        Position $position,
        PositionCandidate $positionCandidate,
        PositionCandidateEvaluationRequestData $data
    ): Collection {
        return DB::transaction(function () use (
            $creator,
            $position,
            $positionCandidate,
            $data,
        ): Collection {
            $collection = modelCollection(PositionCandidateEvaluation::class);

            /** @var User $user */
            foreach ($data->users as $user) {
                $collection->push(
                    $this->positionCandidateEvaluationRepository->store(
                        new PositionCandidateEvaluationStoreInput(
                            creator: $creator,
                            positionCandidate: $positionCandidate,
                            user: $user,
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
