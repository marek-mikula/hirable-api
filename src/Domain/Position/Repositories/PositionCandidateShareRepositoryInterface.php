<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Repositories\Input\PositionCandidateShareStoreInput;

interface PositionCandidateShareRepositoryInterface
{
    public function store(PositionCandidateShareStoreInput $input): PositionCandidateShare;

    public function delete(PositionCandidateShare $positionCandidateShare): void;
}
