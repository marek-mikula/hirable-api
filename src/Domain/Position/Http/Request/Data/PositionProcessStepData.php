<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Spatie\LaravelData\Data;

class PositionProcessStepData extends Data
{
    public ?string $label;
}
