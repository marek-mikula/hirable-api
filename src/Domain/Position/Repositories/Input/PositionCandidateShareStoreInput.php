<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;

readonly class PositionCandidateShareStoreInput
{
    public function __construct(
        public User $creator,
        public PositionCandidate $positionCandidate,
        public User $user,
    ) {
    }
}
