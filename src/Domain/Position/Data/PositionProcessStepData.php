<?php

declare(strict_types=1);

namespace Domain\Position\Data;

use Domain\ProcessStep\Enums\StepEnum;

readonly class PositionProcessStepData
{
    public function __construct(
        public StepEnum|string $step,
        public bool $isFixed,
        public bool $isRepeatable,
    ) {
    }
}
