<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Domain\Position\Enums\EvaluationResultEnum;

readonly class PositionCandidateEvaluationUpdateInput
{
    public function __construct(
        public ?string $evaluation,
        public EvaluationResultEnum|null $result,
    ) {
    }
}
