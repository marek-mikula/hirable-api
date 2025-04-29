<?php

declare(strict_types=1);

namespace Support\Grid\Http\Requests\Data;

use Spatie\LaravelData\Data;

class GridColumnWidthData extends Data
{
    public string $key;

    public int $width;
}
