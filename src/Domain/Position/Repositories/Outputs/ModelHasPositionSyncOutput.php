<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Outputs;

use Illuminate\Support\Collection;

readonly class ModelHasPositionSyncOutput
{
    public function __construct(
        public Collection $stored,
        public Collection $deleted,
    ) {
    }
}
