<?php

namespace Support\Grid\Http\Requests\Data;

use Spatie\LaravelData\Data;

class GridColumnSettingData extends Data
{
    public string $key;

    public bool $enabled;
}
