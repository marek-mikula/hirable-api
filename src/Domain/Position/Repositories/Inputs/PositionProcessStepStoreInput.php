<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Inputs;

use Domain\Company\Models\Company;

readonly class PositionProcessStepStoreInput
{
    public function __construct(
        public Company $company,
        public string $step,
    ) {
    }
}
