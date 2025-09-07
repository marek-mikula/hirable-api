<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Repositories\Input\PositionCandidateShareStoreInput;
use Illuminate\Database\Eloquent\Collection;

interface PositionCandidateShareRepositoryInterface
{
    /**
     * @param string[] $with
     * @return Collection<PositionCandidateShare>
     */
    public function index(PositionCandidate $positionCandidate, array $with = []): Collection;

    public function store(PositionCandidateShareStoreInput $input): PositionCandidateShare;

    public function delete(PositionCandidateShare $positionCandidateShare): void;
}
