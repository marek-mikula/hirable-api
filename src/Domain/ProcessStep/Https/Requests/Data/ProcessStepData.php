<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Requests\Data;

use Spatie\LaravelData\Data;

class ProcessStepData extends Data
{
    public string $step;

    public bool $isRepeatable;
}
