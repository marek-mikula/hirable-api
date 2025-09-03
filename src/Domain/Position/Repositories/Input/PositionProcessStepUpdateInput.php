<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

readonly class PositionProcessStepUpdateInput
{
    public function __construct(
        public ?string $label,
    ) {
    }
}
