<?php

declare(strict_types=1);

namespace Domain\AI\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class CVDataExperience extends Data
{
    public string $position;

    public string $organisation;

    public ?Carbon $from;

    public ?Carbon $to;

    public ?string $type;
}
