<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Data;

use Domain\Position\Models\Position;
use Spatie\LaravelData\Data;

class TokenData extends Data
{
    public Position $position;
}
