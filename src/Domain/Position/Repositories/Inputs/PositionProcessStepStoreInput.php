<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Inputs;

use Domain\Position\Models\Position;
use Domain\ProcessStep\Enums\ProcessStepEnum;

readonly class PositionProcessStepStoreInput
{
    public function __construct(
        public Position $position,
        public int $order,
        public ProcessStepEnum|string $step,
        public ?int $round,
    ) {
    }
}
