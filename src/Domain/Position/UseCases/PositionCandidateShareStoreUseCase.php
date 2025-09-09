<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Repositories\Input\PositionCandidateShareStoreInput;
use Domain\Position\Repositories\PositionCandidateShareRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PositionCandidateShareStoreUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateShareRepositoryInterface $positionCandidateShareRepository,
    ) {
    }

    /**
     * @param Collection<User> $hiringManagers
     * @return Collection<PositionCandidateShare>
     */
    public function handle(User $user, Position $position, PositionCandidate $positionCandidate, Collection $hiringManagers): Collection
    {
        return DB::transaction(function () use (
            $user,
            $position,
            $positionCandidate,
            $hiringManagers,
        ): Collection {
            $collection = modelCollection(PositionCandidateShare::class);

            /** @var User $hiringManager */
            foreach ($hiringManagers as $hiringManager) {
                $collection->push($this->positionCandidateShareRepository->store(
                    new PositionCandidateShareStoreInput(
                        creator: $user,
                        positionCandidate: $positionCandidate,
                        user: $hiringManager,
                    )
                ));
            }

            return $collection;
        }, attempts: 5);
    }
}
