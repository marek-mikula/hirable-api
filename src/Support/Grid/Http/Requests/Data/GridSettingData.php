<?php

namespace Support\Grid\Http\Requests\Data;

use Spatie\LaravelData\Data;
use Support\Grid\Enums\PerPageEnum;

class GridSettingData extends Data
{
    public PerPageEnum $perPage;

    public bool $stickyHeader;

    public bool $stickyFooter;

    /** @var GridColumnSettingData[] */
    public array $columns;
}
