<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Repositories\Inputs;

use Domain\Company\Models\Company;

readonly class ProcessStepStoreInput
{
    public function __construct(
        public Company $company,
        public string $step,
        public bool $isRepeatable,
    ) {
    }
}
