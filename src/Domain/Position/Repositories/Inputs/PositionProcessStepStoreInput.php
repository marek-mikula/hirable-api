<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Inputs;

use Domain\Position\Models\Position;
use Domain\ProcessStep\Enums\StepEnum;

readonly class PositionProcessStepStoreInput
{
    public function __construct(
        public Position $position,
        public StepEnum|string $step,
        public ?string $label,
        public int $order,
        public bool $isFixed,
        public bool $isRepeatable,
    ) {
    }
}
