<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories\Inputs;

readonly class ProcessStepUpdateInput
{
    public function __construct(
        public string $step,
        public bool $isRepeatable,
    ) {
    }
}
