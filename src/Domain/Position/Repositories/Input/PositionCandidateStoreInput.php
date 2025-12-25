<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Domain\Application\Models\Application;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;

readonly class PositionCandidateStoreInput
{
    public function __construct(
        public Position $position,
        public Candidate $candidate,
        public Application|null $application,
        public PositionProcessStep $step,
    ) {
    }
}
