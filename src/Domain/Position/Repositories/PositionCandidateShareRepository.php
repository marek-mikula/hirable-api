<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Repositories\Input\PositionCandidateShareStoreInput;

class PositionCandidateShareRepository implements PositionCandidateShareRepositoryInterface
{
    public function store(PositionCandidateShareStoreInput $input): PositionCandidateShare
    {
        $positionCandidateShare = new PositionCandidateShare();
        $positionCandidateShare->user_id = $input->user->id;
        $positionCandidateShare->position_candidate_id = $input->positionCandidate->id;
        $positionCandidateShare->model_type = $input->model::class;
        $positionCandidateShare->model_id = (int) $input->model->getKey();

        throw_if(!$positionCandidateShare->save(), RepositoryException::stored(PositionCandidateShare::class));

        $positionCandidateShare->setRelation('user', $input->user);
        $positionCandidateShare->setRelation('positionCandidate', $input->positionCandidate);
        $positionCandidateShare->setRelation('model', $input->model);

        return $positionCandidateShare;
    }

    public function delete(PositionCandidateShare $positionCandidateShare): void
    {
        throw_if(!$positionCandidateShare->delete(), RepositoryException::deleted(PositionCandidateShare::class));
    }
}
