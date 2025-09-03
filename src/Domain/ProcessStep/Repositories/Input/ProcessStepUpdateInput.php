<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories\Input;

use Domain\Position\Enums\ActionTypeEnum;

readonly class ProcessStepUpdateInput
{
    public function __construct(
        public string $step,
        public bool $isRepeatable,
        public ?ActionTypeEnum $triggersAction,
    ) {
    }
}
