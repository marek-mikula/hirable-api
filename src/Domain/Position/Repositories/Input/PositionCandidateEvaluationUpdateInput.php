<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Carbon\Carbon;
use Domain\Position\Enums\EvaluationStateEnum;

readonly class PositionCandidateEvaluationUpdateInput
{
    public function __construct(
        public EvaluationStateEnum $state,
        public ?string $evaluation,
        public int|null $stars,
        public ?Carbon $fillUntil,
    ) {
    }
}
