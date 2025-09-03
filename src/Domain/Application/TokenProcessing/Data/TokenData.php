<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Data;

use Domain\Position\Models\Position;

readonly class TokenData
{
    public function __construct(
        public Position $position,
    ) {
    }
}
