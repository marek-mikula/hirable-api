<?php

declare(strict_types=1);

namespace Support\Classifier\Data;

use Spatie\LaravelData\Data;

class ClassifierData extends Data
{
    public string $value;

    public string $label;
}
