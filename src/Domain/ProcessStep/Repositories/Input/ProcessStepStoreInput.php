<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories\Input;

use Domain\Company\Models\Company;
use Domain\Position\Enums\ActionTypeEnum;

readonly class ProcessStepStoreInput
{
    public function __construct(
        public Company $company,
        public string $step,
        public bool $isRepeatable,
        public ?ActionTypeEnum $triggersAction,
    ) {
    }
}
