<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

readonly class PositionCandidateShareStoreInput
{
    public function __construct(
        public User $user,
        public PositionCandidate $positionCandidate,
        public Model $model,
    ) {
    }
}
