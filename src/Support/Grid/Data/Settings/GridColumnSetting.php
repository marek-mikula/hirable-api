<?php

namespace Support\Grid\Data\Settings;

use Spatie\LaravelData\Data;

class GridColumnSetting extends Data
{
    public ?bool $enabled;

    public ?int $width;
}
