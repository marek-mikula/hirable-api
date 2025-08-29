<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Requests\Data;

use Domain\Position\Enums\ActionTypeEnum;

readonly class ProcessStepData
{
    public function __construct(
        public string $step,
        public bool $isRepeatable,
        public ?ActionTypeEnum $triggersAction,
    ) {
    }
}
