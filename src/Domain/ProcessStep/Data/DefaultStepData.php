<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Data;

use Domain\ProcessStep\Enums\ProcessStepEnum;

readonly class DefaultStepData
{
    public function __construct(
        public ProcessStepEnum $step,
        public ?int $round = null,
    ) {
    }
}
