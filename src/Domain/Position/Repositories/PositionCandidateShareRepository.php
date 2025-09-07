<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Repositories\Input\PositionCandidateShareStoreInput;
use Illuminate\Database\Eloquent\Collection;

class PositionCandidateShareRepository implements PositionCandidateShareRepositoryInterface
{
    public function index(PositionCandidate $positionCandidate, array $with = []): Collection
    {
        return PositionCandidateShare::query()
            ->with($with)
            ->where('position_candidate_id', $positionCandidate->id)
            ->get();
    }

    public function store(PositionCandidateShareStoreInput $input): PositionCandidateShare
    {
        $positionCandidateShare = new PositionCandidateShare();
        $positionCandidateShare->position_candidate_id = $input->positionCandidate->id;
        $positionCandidateShare->user_id = $input->user->id;

        throw_if(!$positionCandidateShare->save(), RepositoryException::stored(PositionCandidateShare::class));

        $positionCandidateShare->setRelation('positionCandidate', $input->positionCandidate);
        $positionCandidateShare->setRelation('user', $input->user);

        return $positionCandidateShare;
    }

    public function delete(PositionCandidateShare $positionCandidateShare): void
    {
        throw_if(!$positionCandidateShare->delete(), RepositoryException::deleted(PositionCandidateShare::class));
    }
}
