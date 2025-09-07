<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Domain\Position\Enums\EvaluationResultEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

readonly class PositionCandidateEvaluationStoreInput
{
    public function __construct(
        public User $user,
        public PositionCandidate $positionCandidate,
        public Model $model,
        public ?string $evaluation,
        public EvaluationResultEnum|null $result,
    ) {
    }
}
