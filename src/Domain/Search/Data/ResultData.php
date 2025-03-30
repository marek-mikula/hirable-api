<?php

namespace Domain\Search\Data;

use Spatie\LaravelData\Data;

class ResultData extends Data
{
    public string|int $value;

    public string $label;
}
