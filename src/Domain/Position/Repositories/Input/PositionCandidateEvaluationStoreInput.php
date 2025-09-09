<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Carbon\Carbon;
use Domain\Position\Enums\EvaluationStateEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;

readonly class PositionCandidateEvaluationStoreInput
{
    public function __construct(
        public User $creator,
        public PositionCandidate $positionCandidate,
        public User $user,
        public EvaluationStateEnum $state,
        public ?string $evaluation,
        public int|null $stars,
        public ?Carbon $fillUntil,
    ) {
    }
}
