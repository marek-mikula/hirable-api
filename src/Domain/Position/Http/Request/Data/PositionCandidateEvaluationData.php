<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

readonly class PositionCandidateEvaluationData
{
    public function __construct(
        public string $evaluation,
        public int $stars,
    ) {
    }
}
