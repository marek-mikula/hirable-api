<?php

declare(strict_types=1);

namespace Domain\Position\Data;

use Domain\ProcessStep\Enums\ProcessStepEnum;

readonly class ProcessStepData
{
    public function __construct(
        public ProcessStepEnum|string $step,
        public bool $isFixed,
        public bool $isRepeatable,
    ) {
    }
}
