<?php

namespace Support\Grid\Data\Query;

use Spatie\LaravelData\Data;
use Support\Grid\Enums\OrderEnum;

class GridQuery extends Data
{
    public ?int $page;

    public ?string $searchQuery;

    /** @var array<string,OrderEnum> key is column key */
    public array $sort;
}
