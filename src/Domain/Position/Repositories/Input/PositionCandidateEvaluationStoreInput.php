<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Domain\Position\Enums\EvaluationResultEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;

readonly class PositionCandidateEvaluationStoreInput
{
    public function __construct(
        public PositionCandidate $positionCandidate,
        public User $user,
        public ?string $evaluation,
        public EvaluationResultEnum|null $result,
    ) {
    }
}
